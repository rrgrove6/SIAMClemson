SELECT bq.question_id, br.response_id, answer FROM balderdash_responses AS br LEFT JOIN balderdash_questions AS bq ON bq.question_id = br.question_id WHERE round = 1 AND br.question_id = 1 ORDER BY answer