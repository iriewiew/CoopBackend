<template>
  <v-app>
    <v-card-title>
      <h1>รายงานสถิติการจัดกิจกรรม</h1>
      <v-spacer></v-spacer>
      <v-flex xs12 md3>
        <v-select v-model="id" :items="years" label="ปีการศึกษา" v-on:change="getData" />
      </v-flex>
    </v-card-title>
    <br />
    <div class="text-xs-center">
      <p class="title">
        <b>สถิติการจัดกิจกรมของศูนย์สหกิจศึกษามหาวิทยาลัยราชภัฏลำปาง</b>
      </p>
      <p class="subheading">ปีการศึกษา {{id}}</p>
      <v-container v-if="!dataSource.data.length == 0">
        <fusioncharts
          :type="type"
          :width="width"
          :height="height"
          :dataFormat="dataFormat"
          :dataSource="dataSource"
        ></fusioncharts>
      </v-container>

      <div v-if="dataSource.data == ''" class="headline">
        <br><br><br>
        <v-icon style="  font-size: 99pt;">sentiment_very_dissatisfied</v-icon>
        <br />ไม่พบข้อมูล
      </div>
    </div>
  </v-app>
</template>
<style>
g[class$="creditgroup"] {
  display: none !important;

}
</style>
<script>
import axios from "axios";
export default {
  mounted() {
    this.getData();
  },
  data() {
    return {
      show: false,
      datas: "",
      id: 2562,
      type: "doughnut2d", // The chart type
      width: "60%",
      height: "60%",
      dataFormat: "json", // Data type
      dataSource: {
        // Chart Configuration
        chart: {
          numberSuffix: " กิจกรรม",
          theme: "fusion"
        },
        // Chart Data
        data: []
      }
    };
  },
  methods: {
    getData: function() {
      let apiURL = "http://localhost/coopApi/static.php?id=";
      let getID = this.id;
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID, setting)
        .then(
          response => (this.dataSource.data = response.data),
          (this.show = true)
        )
        .catch(err => {
          if (err.response.status == 401) {
            Swal.fire({
              type: "warning",
              title: "เซสชั่นหมดอายุแล้ว",
              text: "กรุณาเข้าสู่ระบบใหม่",
              showConfirmButton: true
            });
            localStorage.clear();
            this.$store.state.getApiData.tokenkey = null;
          }
          if (err.response.status == 404) {
            Swal.fire({
              type: "warning",
              title: "เกิดข้อผิดพลาด",
              showConfirmButton: true
            });
          }
        });
    }
  },
  computed: {
    // byTerm() {
    //   return this.datas.reduce((acc, term) => {
    //     (acc[term.event_term] = acc[term.event_term] || []).push({
    //       value: term.event_name
    //     });
    //     return acc;
    //   }, {});
    // },

    years() {
      const year = new Date().getFullYear() + 543;
      return Array.from(
        { length: year - 2558 },
        (value, index) => 2559 + index
      );
    }
  }
};
</script>