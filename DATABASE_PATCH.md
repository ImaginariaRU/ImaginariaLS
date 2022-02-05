```sql

ALTER TABLE ls_wall MODIFY COLUMN last_reply varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;
ALTER TABLE ls_wall MODIFY COLUMN ip varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;

ALTER TABLE ls_comment MODIFY COLUMN comment_edit_count int(11) DEFAULT 0 NULL;

```

