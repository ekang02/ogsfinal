<template>
     <div class="btn-toolbar">
        <div class="btn-group">
          <button class="btn btn-primary" v-for="p in pagination.pages" @click.prevent="setPage(p)">{{p}}</button>
        </div>
      </div>
</template>

<script>
export default {
  name: 'pagination',
  props: ['totalRows','perPage'],
  data(){
    return {
      pagination: {}
    }
  },
  created(){
    this.setPage(1);
  },
  methods: {
    setPage(p) {
        this.pagination = this.paginator(this.totalRows, p);
        this.$emit('update:currentPage', p)
      },
      paginate(data) {
        return _.slice(data, this.pagination.startIndex, this.pagination.endIndex + 1)
      },
      paginator(totalItems, currentPage) {
        var startIndex = (currentPage - 1) * this.perPage,
        endIndex = Math.min(startIndex + this.perPage - 1, totalItems - 1);
        return {
          currentPage: currentPage,
          startIndex: startIndex,
          endIndex: endIndex,
          pages: _.range(1, Math.ceil(totalItems / this.perPage) + 1)
        };
      },
  },
  watch:{
      'perPage'(){
          this.$emit('update:perPage',this.perPage);
          this.setPage(1);

      }
  }
}
</script>
