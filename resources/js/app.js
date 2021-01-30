require('alpinejs')

window.addEventListener('scrollIntoView', event => {
  const scrollableContainer = document.querySelector('main')
  const element = document.querySelector(`#${event.detail.elementId}`)

  if (element) {
      const { left, top } = element.getBoundingClientRect()

      scrollableContainer.scrollTo(left, top + scrollableContainer.scrollTop - 120)

      element.focus()

      if (element.select) {
        element.select()
      }
  }
})