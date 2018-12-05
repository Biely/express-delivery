<template>
  <div class="">
    <el-steps :active="step">
      <el-step :title="stepone.title" :description="stepone.time"></el-step>
      <el-step :title="steptwo.title" :description="steptwo.time"></el-step>
      <el-step :title="stepthree.title" :description="stepthree.time"></el-step>
    </el-steps>
  </div>
</template>

<script>
  export default {
    props: ['task'],
    data() {
      return {
        step: 1,
        stepone: {
          title: "提交工单",
          time: ''
        },
        steptwo: {
          title: "",
          time: ''
        },
        stepthree: {
          title: "",
          time: ''
        }
      }
    },
    mounted() {
      console.log(this.task)
      var time = new Date().getTime().toString().substr(0,10)
      this.stepone.title = "提交工单"
      this.stepone.time = this.task.created_at
      this.steptwo.title = "待处理"
      this.steptwo.time = ""
      this.stepthree.title = "未完结"
      this.steptwo.time = ""
      if(this.task.isok >= 1 && this.task.isok <= 3) {
        this.step = 2
        this.steptwo.title = "客服:"+this.task.sname+"已接单"
        this.steptwo.time = this.task.updated_at
        this.stepthree.title = "未完结"
        this.steptwo.time = ""
        if(this.task.isok == 1 && this.task.deadline < time) {
          this.steptwo.title = "客服:"+this.task.sname+"已接单"
          this.steptwo.time = this.task.updated_at
          this.stepthree.title = "已超时"
          this.steptwo.time = ""
        }
        if(this.task.isok > 1) {
          this.step = 3
          this.steptwo.title = "客服:"+this.task.sname+"已接单"
          this.steptwo.time = ""
          this.stepthree.title = "已完结"
          this.steptwo.time = this.task.updated_at
        }
      }else if(this.task.deadline < time) {
        this.stepthree.title = "已超时"
        console.log(time)
        console.log(this.task.deadline)
      }
    }
  }
</script>
