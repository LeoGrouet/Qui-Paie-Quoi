FROM nginx:1.21.3-alpine AS base

FROM base AS prod

COPY ./services/nginx/nginx.prod.conf /etc/nginx/nginx.conf

COPY ./App/public /usr/src/app/public