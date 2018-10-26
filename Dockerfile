FROM oneeg/php:7.2-apache

RUN wget https://github.com/Droplr/aws-env/raw/master/bin/aws-env-linux-amd64 -O /bin/aws-env && \
    chmod +x /bin/aws-env

COPY . /app

CMD eval $(aws-env) && httpd -D FOREGROUND