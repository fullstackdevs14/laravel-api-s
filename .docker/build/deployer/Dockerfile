FROM 5ndn/deployer:1.0
ADD file:c6c23585ab140b0b320d4e99bc1b0eb544c9e96c24d90fec5e069a6d57d335ca in /
CMD ["/bin/bash"]
ENV PHPIZE_DEPS=autoconf                file            g++             gcc             libc-dev                make            pkg-config              re2c
RUN /bin/sh -c apt-get update \
    && apt-get install -y               $PHPIZE_DEPS            ca-certificates                 curl            libedit2                libsqlite3-0            libxml2                 xz-utils        --no-install-recommends \
    && rm -r /var/lib/apt/lists/*
ENV PHP_INI_DIR=/usr/local/etc/php
RUN /bin/sh -c mkdir -p $PHP_INI_DIR/conf.d
ENV GPG_KEYS=1A4E8B7277C42E53DBA9C7B9BCAA30EA9C0D5763
ENV PHP_VERSION=7.0.11
ENV PHP_FILENAME=php-7.0.11.tar.xz
ENV PHP_SHA256=d4cccea8da1d27c11b89386f8b8e95692ad3356610d571253d00ca67d524c735
RUN /bin/sh -c set -xe  \
    && cd /usr/src      \
    && curl -fSL "https://secure.php.net/get/$PHP_FILENAME/from/this/mirror" -o php.tar.xz      \
    && echo "$PHP_SHA256 *php.tar.xz" | sha256sum -c -  \
    && curl -fSL "https://secure.php.net/get/$PHP_FILENAME.asc/from/this/mirror" -o php.tar.xz.asc      \
    && export GNUPGHOME="$(mktemp -d)"  \
    && for key in $GPG_KEYS; do                 gpg --keyserver ha.pool.sks-keyservers.net --recv-keys "$key";  done    \
    && gpg --batch --verify php.tar.xz.asc php.tar.xz   \
    && rm -r "$GNUPGHOME"
COPY file:207c686e3fed4f71f8a7b245d8dcae9c9048d276a326d82b553c12a90af0c0ca in /usr/local/bin/
RUN /bin/sh -c set -xe  \
    && buildDeps="              $PHP_EXTRA_BUILD_DEPS           libcurl4-openssl-dev            libedit-dev             libsqlite3-dev          libssl-dev              libxml2-dev     "       \
    && apt-get update \
    && apt-get install -y $buildDeps --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*              \
    && docker-php-source extract        \
    && cd /usr/src/php  \
    && ./configure              --with-config-file-path="$PHP_INI_DIR"          --with-config-file-scan-dir="$PHP_INI_DIR/conf.d"                               --disable-cgi                           --enable-ftp            --enable-mbstring               --enable-mysqlnd                                --with-curl             --with-libedit          --with-openssl           --with-zlib                             $PHP_EXTRA_CONFIGURE_ARGS       \
    && make -j"$(nproc)"        \
    && make install     \
    && { find /usr/local/bin /usr/local/sbin -type f -executable -exec strip --strip-all '{}' + || true; }      \
    && make clean       \
    && docker-php-source delete                 \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $buildDeps
COPY multi:ed54b4fe7bef284934703fa6e979b7cc0daed0549a07586d0c1ccd4e2b41884a in /usr/local/bin/
CMD ["php" "-a"]
RUN /bin/sh -c apt-get update \
    && apt-get install -y         libfreetype6-dev         libjpeg62-turbo-dev         libmcrypt-dev         libpng12-dev         wget     \
    && docker-php-ext-install -j$(nproc) iconv mcrypt     \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/     \
    && docker-php-ext-install -j$(nproc) gd
MAINTAINER Joel Caballero "joel@fiveanddone.com"
RUN /bin/sh -c apt-get update \
    && apt-get install -y     curl     git     subversion     unzip     \
    && docker-php-ext-install zip     \
    && docker-php-ext-install -j$(nproc) iconv mcrypt     \
    && docker-php-ext-install mysqli pdo pdo_mysql
RUN /bin/sh -c mkdir /root/.ssh/
RUN /bin/sh -c touch /root/.ssh/known_hosts
RUN /bin/sh -c ssh-keyscan bitbucket.org >> /root/.ssh/known_hosts
RUN /bin/sh -c ssh-keyscan github.com >> /root/.ssh/known_hosts
RUN /bin/sh -c curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN /bin/sh -c wget http://deployer.org/deployer.phar
RUN /bin/sh -c mv deployer.phar /usr/local/bin/dep
RUN /bin/sh -c chmod +x /usr/local/bin/dep
VOLUME [/app]
VOLUME [/root/.ssh]
WORKDIR /app