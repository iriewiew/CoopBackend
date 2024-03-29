<template>
  <v-app>
    <v-card-title>
      <h1>หัวข้อกิจกรรม</h1>
      <v-spacer></v-spacer>
      <v-text-field
        v-show="!searchShow"
        v-model="search"
        append-icon="close"
        label="ค้นหา"
        single-line
        hide-details
        @click:append="searchShow = !searchShow,search= null"
      ></v-text-field>

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on" v-show="searchShow" @click="searchShow = !searchShow" color="gray">
            <v-icon>search</v-icon>
          </v-btn>
        </template>
        <span>ค้นหาข้อมูล</span>
      </v-tooltip>

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon color="gray" v-on="on" @click="addButton">
            <v-icon>add</v-icon>
          </v-btn>
        </template>
        <span>เพิ่มข้อมูล</span>
      </v-tooltip>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="data"
      :search="search"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
    >
      <template v-slot:items="props">
        <td>
          <v-icon>event</v-icon>
        </td>
        <td width="35%" class="text-xs-left">{{ props.item.event_name }}</td>
        <td class="text-xs-left">
          <b>{{ props.item.event_date | moment("DD MMMM YYYY")}}</b>
          <br />
          เวลา {{ props.item.event_start }} - {{ props.item.event_end }} น.
        </td>

        <td class="text-xs-left">
          <v-chip v-if="props.item.event_date == props.item.todayDate" outline color="green">เปิด</v-chip>
          <v-chip v-else outline color="red">ปิด</v-chip>
        </td>

        <td class="text-xs-left">
          <div v-if="props.item.event_date == props.item.todayDate">
            <v-chip v-if="props.item.event_eva_status == 1" outline color="green">เปิด</v-chip>
            <v-chip v-else outline color="red">ปิด</v-chip>
          </div>
          <div v-else>
            <v-chip outline color="red">ปิด</v-chip>
          </div>
        </td>
        <td class="text-xs-right">
          <v-btn flat icon v-bind:to="{name: 'qrCode', params: {eventID: props.item.id}}">
            <v-icon>mdi-qrcode-scan</v-icon>
          </v-btn>
        </td>
        <td class="text-xs-right">
          <v-menu offset-y>
            <template v-slot:activator="{ on }">
              <v-btn icon v-on="on" color="gray">
                <v-icon>person_add</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-tile
                :key="index"
                v-bind:to="{name: 'EventAddStu', params: {eventID: props.item.id}}"
              >
                <v-list-tile-title>นักศึกษา</v-list-tile-title>
              </v-list-tile>
            </v-list>
            <v-list>
              <v-list-tile
                :key="index1"
                v-bind:to="{name: 'EventAddTeac', params: {eventID: props.item.id}}"
              >
                <v-list-tile-title>คณาจารย์</v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-menu>
        </td>
        <td class="text-xs-right">
          <v-btn
            flat
            icon
            color="blue"
            v-bind:to="{name: 'eventDetail', params: {eventID: props.item.id}}"
          >
            <v-icon>create</v-icon>
          </v-btn>
          <v-btn
            flat
            icon
            color="pink"
            @click="(selectedData.id = props.item.id,selectedData.name = props.item.event_name),dialogDel() "
          >
            <v-icon>delete_outline</v-icon>
          </v-btn>
        </td>
      </template>
      <template v-slot:no-results>
        <v-alert :value="true" color="error" icon="warning">ไม่พบข้อมูล "{{ search }}" .</v-alert>
      </template>
      <template v-slot:footer>
        <td :colspan="headers.length">
          <strong>จำนวนกิจกรรมทั้งหมด {{data.length}} กิจกรรม</strong>
        </td>
      </template>
    </v-data-table>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getData();
  },
  data() {
    return {
      search: "",
      searchShow: true,
      data: [],
      rowsPerPageItems: [9, 18, 27, 32, 99],
      pagination: {
        rowsPerPage: 9,
        descending: true,
        sortBy: "event_date"
      },
      selectedData: { id: "", name: "" },
      headers: [
        {
          text: "",
          align: "left",
          sortable: false,
          value: "event_end"
        },
        { text: "รายชื่อกิจกรรม", value: "event_name" },
        { text: "วันที่ดำเนินการ", value: "event_date" },
        { text: "การลงทะเบียน", value: "status" },
        { text: "การประเมิน", value: "event_eva_staus" },
        { text: "รับ qr-code", sortable: false },
        { text: "เพิ่มผู้เข้าร่วม", sortable: false },
        { text: "จัดการ", align: "right", value: "null", sortable: false }
      ]
    };
  },
  methods: {
    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/event/datatable", setting)
        .then(
          response => (
            (this.data = response.data.data), (this.isLoading = false)
          )
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
    },
    dialogDel: function() {
      Swal.fire({
        title: "ยืนยันการลบข้อมูล?",
        text:
          "ต้องการลบข้อมูล " +
          "' " +
          this.selectedData.name +
          " '" +
          " หรือไม่?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F56C6C",

        cancelButtonText: "ยกเลิก",
        confirmButtonText: "ยืนยัน"
      }).then(result => {
        if (result.value) {
          this.deleteData();
          console.log("ok1");
        }
      });
    },
    deleteData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      let apiURL = "http://localhost:1337/event/delete/";
      let getID = this.selectedData;
      this.axios
        .post(apiURL, getID, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "ลบข้อมูล " + this.selectedData.name + " เรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.getData();
          });
        })
        .catch(err => {
          console.log(err);
          Swal.fire({
            type: "error",
            title: "เกิดข้อผิดพลาด",
            showConfirmButton: false,
            timer: 1500
          });
        });
    },
    addButton: function() {
      this.$router.push("/event/add");
    }
  }
};
</script>