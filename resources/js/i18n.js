import { createI18n } from 'vue-i18n'

const esModules = import.meta.glob('./lang/es/*.json', {
  eager: true,
})

const enModules = import.meta.glob('./lang/en/*.json', {
  eager: true,
})

function buildMessages(modules) {
  const messages = {}

  Object.entries(modules).forEach(([path, module]) => {
    const file = path.split('/').pop().replace('.json', '')

    messages[file] = module.default
  })

  return messages
}

export const createAppI18n = (locale = 'es') => {
  return createI18n({
    legacy: false,
    locale,
    fallbackLocale: 'es',
    messages: {
      es: buildMessages(esModules),
      en: buildMessages(enModules),
    },
  })
}