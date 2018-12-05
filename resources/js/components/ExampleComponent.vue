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
      const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        });
        setTimeout(() => {
          loading.close();
        }, 2000);
      axios.get(String(this.rurl), {
          params: {
            score: this.score
          }
        }).then((res) => {
          if(res.data.status === 'success') {
            this.dialogFormVisible = false
            this.$message({
              message: '评价成功！',
              type: 'success'
            })
            location.reload()
          }else{
            this.dialogFormVisible = false
            this.$message({
              message: '评价失败！',
              type: 'warning'
            })
          }
        })
     },
     moretask(){
       const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        });
        setTimeout(() => {
          loading.close();
        }, 2000);
       axios.post(String(this.turl), {
           params: {
           }
         }).then((res) => {
           if(res.data.status === 'success') {
             this.$message({
               message: '投诉成功！',
               type: 'success'
             })
             location.reload()
           }else{
             this.$message({
               message: '投诉失败！',
               type: 'warning'
             })
           }
         })
     }
  }
}
</script>
