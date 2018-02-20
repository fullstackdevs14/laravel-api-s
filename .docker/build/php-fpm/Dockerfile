FROM 5ndn/php-fpm:7.0
ADD file:9c48682ff75c756544d4491472081a078edf5dd0bb5038d1cb850a1f9c480e3e in /
CMD ["bash"]
ENV PHPIZE_DEPS=autoconf                dpkg-dev                file            g++             gcc             libc-dev                libpcre3-dev            make            pkg-config              re2c
RUN /bin/sh -c apt-get update \
    && apt-get install -y               $PHPIZE_DEPS            ca-certificates                 curl            libedit2                libsqlite3-0            libxml2                 xz-utils        --no-install-recommends \
    && rm -r /var/lib/apt/lists/*
ENV PHP_INI_DIR=/usr/local/etc/php
RUN /bin/sh -c mkdir -p $PHP_INI_DIR/conf.d
ENV PHP_EXTRA_CONFIGURE_ARGS=--enable-fpm --with-fpm-user=www-data --with-fpm-group=www-data
ENV PHP_CFLAGS=-fstack-protector-strong -fpic -fpie -O2
ENV PHP_CPPFLAGS=-fstack-protector-strong -fpic -fpie -O2
ENV PHP_LDFLAGS=-Wl,-O1 -Wl,--hash-style=both -pie
ENV GPG_KEYS=1A4E8B7277C42E53DBA9C7B9BCAA30EA9C0D5763 6E4F6AB321FDC07F2C332E3AC2BF0BC433CFC8B3
ENV PHP_VERSION=7.0.21
ENV PHP_URL=https://secure.php.net/get/php-7.0.21.tar.xz/from/this/mirror PHP_ASC_URL=https://secure.php.net/get/php-7.0.21.tar.xz.asc/from/this/mirror
ENV PHP_SHA256=6713fe3024365d661593235b525235045ef81f18d0043654658c9de1bcb8b9e3 PHP_MD5=
RUN /bin/sh -c set -xe;                 fetchDeps='             wget    ';      if ! command -v gpg > /dev/null; then           fetchDeps="$fetchDeps                   dirmngr                         gnupg2          ";      fi;     apt-get update;         apt-get install -y --no-install-recommends $fetchDeps;  rm -rf /var/lib/apt/lists/*;            mkdir -p /usr/src;       cd /usr/src;            wget -O php.tar.xz "$PHP_URL";          if [ -n "$PHP_SHA256" ]; then           echo "$PHP_SHA256 *php.tar.xz" | sha256sum -c -;        fi;     if [ -n "$PHP_MD5" ]; then              echo "$PHP_MD5 *php.tar.xz" | md5sum -c -;      fi;             if [ -n "$PHP_ASC_URL" ]; then          wget -O php.tar.xz.asc "$PHP_ASC_URL";           export GNUPGHOME="$(mktemp -d)";                for key in $GPG_KEYS; do                        gpg --keyserver ha.pool.sks-keyservers.net --recv-keys "$key";          done;           gpg --batch --verify php.tar.xz.asc php.tar.xz;                 rm -rf "$GNUPGHOME";    fi;             apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $fetchDeps
COPY file:207c686e3fed4f71f8a7b245d8dcae9c9048d276a326d82b553c12a90af0c0ca in /usr/local/bin/
RUN /bin/sh -c set -xe  \
    && buildDeps="              $PHP_EXTRA_BUILD_DEPS           libcurl4-openssl-dev            libedit-dev             libsqlite3-dev          libssl-dev              libxml2-dev             zlib1g-dev      "       \
    && apt-get update \
    && apt-get install -y $buildDeps --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*              \
    && export CFLAGS="$PHP_CFLAGS"              CPPFLAGS="$PHP_CPPFLAGS"                LDFLAGS="$PHP_LDFLAGS"  \
    && docker-php-source extract        \
    && cd /usr/src/php  \
    && gnuArch="$(dpkg-architecture --query DEB_BUILD_GNU_TYPE)"        \
    && debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"  \
    && if [ ! -d /usr/include/curl ]; then              ln -sT "/usr/include/$debMultiarch/curl" /usr/local/include/curl;       fi      \
    && ./configure              --build="$gnuArch"              --with-config-file-path="$PHP_INI_DIR"          --with-config-file-scan-dir="$PHP_INI_DIR/conf.d"                               --disable-cgi                           --enable-ftp            --enable-mbstring               --enable-mysqlnd                                --with-curl     --with-libedit           --with-openssl          --with-zlib                             --with-pcre-regex=/usr          --with-libdir="lib/$debMultiarch"                               $PHP_EXTRA_CONFIGURE_ARGS       \
    && make -j "$(nproc)"       \
    && make install     \
    && { find /usr/local/bin /usr/local/sbin -type f -executable -exec strip --strip-all '{}' + || true; }      \
    && make clean       \
    && cd /     \
    && docker-php-source delete                 \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $buildDeps          \
    && pecl update-channels     \
    && rm -rf /tmp/pear ~/.pearrc
COPY multi:1401feee8064a06ad514519ec870939c946ecfdf381c82a90cb2035486938ee9 in /usr/local/bin/
ENTRYPOINT ["docker-php-entrypoint"]
WORKDIR /var/www/html
RUN /bin/sh -c set -ex  \
    && cd /usr/local/etc        \
    && if [ -d php-fpm.d ]; then                sed 's!=NONE/!=!g' php-fpm.conf.default | tee php-fpm.conf > /dev/null;                 cp php-fpm.d/www.conf.default php-fpm.d/www.conf;       else            mkdir php-fpm.d;                cp php-fpm.conf.default php-fpm.d/www.conf;             {                       echo '[global]';                echo 'include=etc/php-fpm.d/*.conf';             } | tee php-fpm.conf;   fi      \
    && {                echo '[global]';                echo 'error_log = /proc/self/fd/2';             echo;           echo '[www]';           echo '; if we send this to /proc/self/fd/1, it never appears';          echo 'access.log = /proc/self/fd/2';            echo;           echo 'clear_env = no';          echo;           echo '; Ensure worker stdout and stderr are sent to the main error log.';                echo 'catch_workers_output = yes';      } | tee php-fpm.d/docker.conf   \
    && {                echo '[global]';                echo 'daemonize = no';          echo;           echo '[www]';           echo 'listen = [::]:9000';      } | tee php-fpm.d/zz-docker.conf
EXPOSE 9000/tcp
CMD ["php-fpm"]
RUN /bin/sh -c apt-get update \
    && apt-get install -y         git         libmemcached-dev         zlib1g-dev         libfreetype6-dev         libjpeg62-turbo-dev         libmcrypt-dev         libpng12-dev         libmemcached-dev         memcached         libxml2-dev         php-soap         libpq-dev     \
    && git clone --branch php7 https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/     \
    && docker-php-ext-install memcached     \
    && docker-php-ext-install -j$(nproc) iconv mcrypt     \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/     \
    && docker-php-ext-install -j$(nproc) gd     \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql     \
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql     \
    && docker-php-ext-install -j$(nproc) soap
COPY file:ecdc9047f8507b2841a4ae633e6032921da5137df1d93510326660484d42663c in /usr/local/etc/php/conf.d/php.ini
VOLUME [/usr/local/etc/php/conf.d]
VOLUME [/var/www/html]
