import { defineNuxtConfig } from 'nuxt/config'
import vuetify from 'vite-plugin-vuetify'
require('dotenv').config()

export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      env: process.env,
    },
  },
  ssr: true,
  app: {
    head: {
      htmlAttrs: {
        lang: 'ja',
        prefix: 'og: http://ogp.me/ns#',
      },
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        {
          hid: 'og:title',
          property: 'og:title',
          content: 'NuxTemp',
        },
        {
          hid: 'description',
          name: 'description',
          content: 'Nuxtを簡単に構築するサンプル',
        },
        {
          hid: 'og:description',
          property: 'og:description',
          content: 'Nuxtを簡単に構築するサンプル',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/img/icon.png',
        },
        { name: 'format-detection', content: 'telephone=no' },
        { name: 'mobile-web-app-capable', content: 'yes' },
        { name: 'apple-mobile-web-app-capable', content: 'yes' },
        { name: 'apple-mobile-web-app-status-bar-style', content: 'black' },
        { name: 'apple-mobile-web-app-title', content: 'APP_TITLE' },
        { name: 'theme-color', content: '#000000' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
        { rel: 'manifest', href: '/manifest.json' },
      ],
    },
  },
  build: {
    transpile: ['vuetify'],
  },
  hooks: {
    'vite:extendConfig': (config) => {
      config.plugins!.push(vuetify())
    },
  },
  vite: {
    ssr: {
      noExternal: ['vuetify'],
    },
    define: {
      'process.env.DEBUG': false,
    },
  },
  css: [
    '@/assets/main.scss',
    'vuetify/lib/styles/main.sass',
    '@mdi/font/css/materialdesignicons.css',
  ],
  modules: ['@pinia/nuxt', '@pinia-plugin-persistedstate/nuxt'],
})
