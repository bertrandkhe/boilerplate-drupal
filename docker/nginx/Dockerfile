FROM bertrandkhe/nginx-alpine:1.21

RUN sed -i "s/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g" /etc/apk/repositories
COPY ./docker/nginx/etc/templates /etc/nginx/templates