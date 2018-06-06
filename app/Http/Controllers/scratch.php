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

aws acm request-certificate --domain-name www.otruvez.com --subject-alternative-names otruvez.com

RewriteCond %{HTTP_HOST} ^otruvez\.com [NC]
RewriteCond %{SERVER_PORT} 80
RewriteRule ^(.*)$ https://www.otruvez.com/$1 [R=301,L]

php

php artisan search:reindex


docker run -d -p 9200:9200 elasticsearch


ps aux | egrep '(apache|httpd)'
chown -R webapp:webapp /var/www/html

sudo find /var/www/html/ -type d -exec chmod 755 {} \;
sudo find /var/www/html/ -type f -exec chmod 644 {} \;

sudo chgrp -R webapp /var/www/html/storage /var/www/html/bootstrap/cache
sudo chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

curl -XDELETE 'localhost:9200/plans?pretty'
curl -XDELETE 'https://vpc-otruvez-es-omtlemroamzj2e4llaalijwiaa.us-west-2.es.amazonaws.com/plans?pretty'