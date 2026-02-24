module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./**/*.js"
  ],
safelist: [
  'hover:bg-sena-soft',
  'hover:text-sena-text-main',
  'bg-sena-soft',
  'text-sena-strong',
  'text-sena-text-muted',
  'text-sena-text-main'
],
  theme: {
    extend: {
      colors: {
        sena: {
          DEFAULT: "#39A900",
          hover: "#2d8700",
          soft: "#e9f6df",
          strong: "#007832",
          dark: "#00304D",
          border: "#e5e7eb",
          text: {
            main: "#1a2620",
            muted: "#6b756e",
            soft: "#737373"
          }
        }
      }
    }
  }
}