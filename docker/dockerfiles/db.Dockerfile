FROM mysql:5.7

RUN yum install -y zip unzip

WORKDIR /home

