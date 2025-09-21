import { existsSync, rmSync, writeFileSync } from 'node:fs'
import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'

export default defineConfig(({ mode }) => {
  const root = process.cwd()
  const hotFile = 'hot'
  let exitHandlersBound = false

  return {
    base: `${root.slice(root.indexOf('/wp-content'))}/${mode !== 'development' ? 'dist/' : ''}`,
    build: {
      manifest: 'manifest.json',
      rollupOptions: {
        input: ['style.css', 'main.js'],
        output: {
          manualChunks: {
            '@mux/mux-player': ['@mux/mux-player'],
            '@formkit/auto-animate': ['@formkit/auto-animate'],
            '@alpinejs/collapse': ['@alpinejs/collapse'],
            '@alpinejs/focus': ['@alpinejs/focus'],
            '@alpinejs/resize': ['@alpinejs/resize'],
            alpine: ['alpinejs'],
          }
        }
      }
    },
    server: {
      cors: true,
    },
    plugins: [
      tailwindcss(),
      {
        name: 'hot',
        configureServer ({ config, httpServer }) {
          httpServer.once('listening', () => {
            const address = httpServer?.address()
            const configHmrProtocol = typeof config.server.hmr === 'object' ? config.server.hmr.protocol : null
            const clientProtocol = configHmrProtocol ? (configHmrProtocol === 'wss' ? 'https' : 'http') : null
            const serverProtocol = config.server.https ? 'https' : 'http'
            const protocol = clientProtocol ?? serverProtocol
            const configHmrHost = typeof config.server.hmr === 'object' ? config.server.hmr.host : null
            const configHost = typeof config.server.host === 'string' ? config.server.host : null
            const serverAddress = address.family === 'IPv6' ? `[${address.address}]` : address.address
            const host = configHmrHost ?? configHost ?? serverAddress
            const configHmrClientPort = typeof config.server.hmr === 'object' ? config.server.hmr.clientPort : null
            const port = configHmrClientPort ?? address.port
            const viteDevServerUrl = `${protocol}://${host}:${port}`

            writeFileSync(hotFile, viteDevServerUrl)
          })

          if (! exitHandlersBound) {
            const clean = () => {
              if (existsSync(hotFile)) {
                rmSync(hotFile)
              }
            }

            process.on('exit', clean)
            process.on('SIGINT', () => process.exit())
            process.on('SIGTERM', () => process.exit())
            process.on('SIGHUP', () => process.exit())

            exitHandlersBound = true
          }
        }
      }
    ]
  }
})
