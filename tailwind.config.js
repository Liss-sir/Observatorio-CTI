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
    'text-sena-text-main',
    'focus:border-sena',
    'focus:ring-sena'
  ],
  theme: {
    extend: {
      colors: {
        sena: "#39A900",  
        'sena-hover': "#2d8700",
        'sena-soft': "#e9f6df",
        'sena-strong': "#007832",
        'sena-dark': "#00304D",
        'sena-border': "#e5e7eb",
        'sena-text': {
          main: "#1a2620",
          muted: "#6b756e",
          soft: "#737373"
        }
      }
    }
  }
}