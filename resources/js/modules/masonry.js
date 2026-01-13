export default function masonry() {
  return {
    columns: 4,
    gap: 0,
    containerWidth: 0,
    
    init() {
      this.calculateColumns()
      this.$nextTick(() => this.layout())
      
      // Re-layout after Livewire updates
      document.addEventListener('livewire:navigated', () => {
        this.$nextTick(() => this.layout())
      })
      
      Livewire.hook('morph.updated', () => {
        this.$nextTick(() => this.layout())
      })
    },
    
    calculateColumns() {
      const width = this.$refs.container?.offsetWidth || window.innerWidth
      
      if (width < 640) {
        this.columns = 2
      } else if (width < 768) {
        this.columns = 3
      } else {
        this.columns = 4
      }
    },
    
    layout() {
      const container = this.$refs.container
      if (!container) return
      
      this.calculateColumns()
      
      const items = container.querySelectorAll('.masonry-item')
      if (!items.length) return
      
      const containerWidth = container.offsetWidth
      const columnWidth = (containerWidth - (this.gap * (this.columns - 1))) / this.columns
      const columnHeights = new Array(this.columns).fill(0)
      
      items.forEach((item) => {
        // Find shortest column
        const shortestColumn = columnHeights.indexOf(Math.min(...columnHeights))
        
        // Position item
        const x = shortestColumn * (columnWidth + this.gap)
        const y = columnHeights[shortestColumn]
        
        item.style.position = 'absolute'
        item.style.width = `${columnWidth}px`
        item.style.left = `${x}px`
        item.style.top = `${y}px`
        
        // Update column height
        columnHeights[shortestColumn] += item.offsetHeight + this.gap
      })
      
      // Set container height
      container.style.height = `${Math.max(...columnHeights) - this.gap}px`
    }
  }
}
