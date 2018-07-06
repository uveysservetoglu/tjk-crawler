# tjk-crawler

Türkiye Jokey Kulübü (tjk) Daily Racing Crawl

Step 1: Git Clone
---
    git clone https://github.com/uveysservetoglu/tjk-crawler.git
---

After making the database connection

---

Step 2 : Entity Created
---
    php bin/console doctrine:schema:update --force
---

Step 3 : Crawler Run
---
    php bin/console crawl:tjk -t cityName
---

Have Fun! :)