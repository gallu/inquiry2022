<?php  // Inquiry.php

class Inquiry {
    // 検索項目
    private const SEARCH_NAME = [
        "name" => "string",
        "email" => "string",
        "tel" => "string",
        "from_at" => "string|date",
        "to_at" => "string|date",
        "reply" => "array",
    ];
    // ページネーションのkey名
    private const PAGE_KEY_NAME = 'p';
    // 1ページあたりの要素数
    private const PAR_PAGE = 5;
    
    /**
     * お問い合わせ一覧の取得
     */
    public static function getList(array $params): array {
//var_dump($params);
        // ページ数を把握
        $page_num = intval($params[self::PAGE_KEY_NAME] ?? 1);
        if (1 > $page_num) {
            $page_num = 1;
        }
//var_dump($page_num);

        // 検索情報を把握
        $search = [];
        foreach(self::SEARCH_NAME as $k => $v) {
            // 値を把握する
            $value = $params[$k] ?? null;
            
            // validation + filterする
            $validate_types = explode('|', $v);
            foreach($validate_types as $type){
                // validate
                if ('string' === $type) {
                    // string用のfilter
                    $value = (string)$value;
                }
                if ('array' === $type) {
                    // array用のfilter
                    $value = (array)$value;
                }
                if ('date' === $type) {
                    // date用のvalidation
                    $t = strtotime($value);
                    if (false === $t) {
                        // invalid なのでデータを消す
                        $value = '';
                    } else {
                        $value = date('Y-m-d', $t);
                    }
                }
            }
            // filterされた値を検索条件として確保
            $search[$k] = $value;
        }
//var_dump($search);

        // DBハンドルを取得
        $dbh = Db::getHandle();

        // プリペアドステートメントを作成
        $where = [];
        $bind = [];
        $sql = 'SELECT * FROM inquiries ';

        // 名前による検索
        if ("" !== $search["name"]) {
            $where[] = 'name LIKE :name';
            $bind[":name"] = "%{$search["name"]}%";
        }
        // emailによる検索
        if ("" !== $search["email"]) {
            $where[] = 'email LIKE :email';
            $bind[":email"] = "%{$search["email"]}%";
        }
        // telによる検索
        if ("" !== $search["tel"]) {
            $where[] = 'tel = :tel';
            $bind[":tel"] = $search["tel"];
        }
        // 受付日付 from
        if ("" !== $search["from_at"]) {
            $where[] = 'created_at >= :from_at';
            $bind[":from_at"] = $search["from_at"];
        }
        // 受付日付 to
        if ("" !== $search["to_at"]) {
            $where[] = 'created_at <= :to_at';
            $bind[":to_at"] = $search["to_at"];
        }
        // reply
        if ([] !== $search["reply"]) {
            $reply_where = [];
            foreach($search["reply"] as $v) {
                if ('0' === $v) {
                    $reply_where[] = 'reply_at IS NULL';
                }
                if ('1' === $v) {
                    $reply_where[] = 'reply_at IS NOT NULL';
                }
            }
            // where句を合成する
            $reply_where_string = implode(' OR ', $reply_where);
            $where[] = "({$reply_where_string})";
        }

        //
        if ([] !== $where) {
            $sql .= ' WHERE ' . implode(" AND ", $where);
        }
        $sql .= ' ORDER BY inquiry_id DESC LIMIT :limit OFFSET :offset;';
//var_dump($sql);
        $pre = $dbh->prepare($sql);

        // 値をバインド
        foreach($bind as $k => $v) {
            $pre->bindValue($k, $v);
        }
        // ページネーション用の値をバインド
        $pre->bindValue(':limit', self::PAR_PAGE + 1, \PDO::PARAM_INT);
        $pre->bindValue(':offset', ($page_num - 1) * self::PAR_PAGE, \PDO::PARAM_INT);

        // SQLを実行
        $r = $pre->execute();
        
        // データを取得
        $data = $pre->fetchAll();
        
        // 「次のページ」の有無の判断
        if (count($data) === self::PAR_PAGE + 1) {
            // 「次のページ」があるので、フラグをon、取得データの末尾を削除する
            array_pop($data);
            $newer_flg = true;
        } else {
            // 「次のページ」がないので、フラグをoff
            $newer_flg = false;
        }

        return [$data, $search, $page_num, $newer_flg];
    }

    /**
     * お問い合わせ１件の取得
     */
    public static function find(string $id): array|false {
        // DBハンドルを取得
        $dbh = Db::getHandle();

        // プリペアドステートメントを作成
        $sql = 'SELECT * FROM inquiries WHERE inquiry_id = :inquiry_id FOR UPDATE;';
        $pre = $dbh->prepare($sql);

        // 値をバインド
        $pre->bindValue(":inquiry_id", $id);

        // SQLを実行
        $r = $pre->execute();
        
        // データを取得
        $datum = $pre->fetch();

        return $datum;
    }

}
