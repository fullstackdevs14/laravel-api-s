FROM 5ndn/nginx:1.11
ADD file:4eedf861fb567fffb2694b65ebdd58d5e371a2c28c3863f363f333cb34e5eb7b in /
CMD ["/bin/bash"]
MAINTAINER NGINX Docker Maintainers "docker-maint@nginx.com"
ENV NGINX_VERSION=1.11.13-1~jessie
RUN /bin/sh -c set -e;  NGINX_GPGKEY=573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62;  found='';       for server in           ha.pool.sks-keyservers.net              hkp://keyserver.ubuntu.com:80           hkp://p80.pool.sks-keyservers.net:80            pgp.mit.edu     ; do            echo "Fetching GPG key $NGINX_GPGKEY from $server";             apt-key adv --keyserver "$server" --keyserver-options timeout=10 --recv-keys "$NGINX_GPGKEY" \
    && found=yes \
    && break;   done;   test -z "$found" \
    && echo >&2 "error: failed to fetch GPG key $NGINX_GPGKEY" \
    && exit 1;  exit 0
RUN /bin/sh -c echo "deb http://nginx.org/packages/mainline/debian/ jessie nginx" >> /etc/apt/sources.list      \
    && apt-get update   \
    && apt-get install --no-install-recommends --no-install-suggests -y                                                 ca-certificates                                                 nginx=${NGINX_VERSION}                                          nginx-module-xslt                                               nginx-module-geoip                      nginx-module-image-filter                                                nginx-module-perl                                               nginx-module-njs                                                gettext-base    \
    && rm -rf /var/lib/apt/lists/*
RUN /bin/sh -c ln -sf /dev/stdout /var/log/nginx/access.log     \
    && ln -sf /dev/stderr /var/log/nginx/error.log
EXPOSE 443/tcp 80/tcp
CMD ["nginx" "-g" "daemon off;"]
EXPOSE 80/tcp
EXPOSE 443/tcp
RUN /bin/sh -c rm /etc/nginx/conf.d/default.conf
COPY file:f4421f96e0eb2e97dd2561539cd2477125d6f0c44e652d305188f3f6ac00993b in /etc/nginx/conf.d/vhost.conf
VOLUME [/etc/nginx/conf.d]
VOLUME [/var/www/html]
