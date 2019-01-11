php artisan make:mail WelcomeBusiness --markdown=emails.business.welcome-business
php artisan make:mail SubscribedUser --markdown=emails.account.subscribed-user
php artisan make:mail UnsubscribedUser --markdown=emails.account.unsubscribed-user
php artisan make:mail FailedPayment --markdown=emails.account.failed-payment

php artisan make:mail NotifyPlanDeletion --markdown=emails.account.notify-plan-deletion
php artisan make:mail NotifyPlanModification --markdown=emails.account.notify-plan-modification
php artisan make:mail NotifyBusinessDeletion --markdown=emails.account.notify-business-deletion
php artisan make:mail NotifyBusinessModification --markdown=emails.account.notify-Business-modification
php artisan make:mail MessageToCustomers --markdown=emails.account.message-to-customers



curl 'localhost:9200/plans/_search?pretty' -d '
{
    "query": {
        "match_phrase": { "stripe_plan_name": {"query":"ad","analyzer":"english"} }
    }
}'

curl -XPUT 'localhost:9200/plans/plans' -d '
{
"settings": {
"analysis": {
"analyzer": {
"english": {
"lowercase": true
}
}
}
}
}'





<!---->

curl -XPUT 'localhost:9200/plans?pretty' -H 'Content-Type: application/json' -d'
{
"settings": {
"analysis": {
"analyzer": {
"english": {
"tokenizer": "whitespace",
"lowercase": true
}
}
}
}
}
'
curl -XPOST 'localhost:9200/my_index/my_type?pretty' -H 'Content-Type: application/json' -d'
{
"first_name": "jawning bruce",
"age": "26"
}
'



php

php artisan search:reindex


docker run -d -p 9200:9200 elasticsearch
docker run -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" docker.elastic.co/elasticsearch/elasticsearch:6.5.4
