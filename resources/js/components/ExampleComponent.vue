<template>
  <div class="form-group">
    <el-row type="flex" class="row-bg" justify="end">
       <el-col :span="8">
         <el-button v-if="task.score == null" type="success" @click="dialogFormVisible = true">评价客服</el-button>
         <el-button type="primary" @click="moretask()">再次投诉</el-button>
       </el-col>
    </el-row>
    <el-dialog title="评价客服" :visible.sync="dialogFormVisible" width="25%">
      <el-rate
        v-model="score"
        :colors="['#99A9BF', '#F7BA2A', '#FF9900']" show-text>
      </el-rate>
      <div slot="footer" class="dialog-footer">
    <el-button type="primary" @click="submitForm()">确 定</el-button>
  </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: ['task','rurl','turl'],
  data() {
    return {
      dialogFormVisible: false,
      score: 0
    }
  },
  methods: {
    submitForm() {
      let that = this
      axios.post(that.rurl, {
          params: {
            score: that.score
          }
        }).then((res) => {
          if(res.data.status === 'success') {
            that.dialogFormVisible = false
            that.$message({
              message: '评价成功！',
              type: 'success'
            })
          }else{
            that.dialogFormVisible = false
            that.$message({
              message: '评价失败！',
              type: 'warning'
            })
          }
        })
     },
     moretask(){
       let that = this
       axios.post(that.turl, {
           params: {
           }
         }).then((res) => {
           if(res.data.status === 'success') {
             that.$message({
               message: '投诉成功！',
               type: 'success'
             })
             location.reload()
           }else{
             that.$message({
               message: '投诉失败！',
               type: 'warning'
             })
           }
         })
     }
  },
  mounted() {
    console.log(this.rurl)
  }
}
</script>
