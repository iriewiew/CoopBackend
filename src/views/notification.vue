<template>
  <v-app>
    <v-card-title>
      <h1>การแจ้งเตือนกิจกรรม</h1>
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
        <span>สร้างการแจ้งเตือนใหม่</span>
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
          <v-icon>insert_comment</v-icon>
        </td>
        <td width="80%" class="text-xs-left">{{ props.item.notification_msg }}</td>
        <td>
          <v-chip outline color="blue">{{props.item.updatedAt | moment("from", "now")}}</v-chip>
        </td>
        <td class="text-xs-right">
          <v-btn
            flat
            icon
            color="gray"
            @click="(sendNotify.fcm.notification.body = props.item.notification_msg ,sendNotify.fcm.notification.id = props.item.id),dialogSend() "
          >
            <v-icon>refresh</v-icon>
          </v-btn>
        </td>
        <td class="text-xs-right">
          <v-btn
            flat
            icon
            color="pink"
            @click="(selectedData.id = props.item.id,selectedData.name = props.item.notification_msg),dialogDel() "
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
          <strong>การแจ้งเตือนทั้งหมด {{data.length}} รายการ</strong>
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
        sortBy: "updatedAt"
      },
      sendNotify: {
        interests: ["hello"],
        fcm: {
          notification: {
            title: "ศูนย์สหกิจศึกษา",
            body: ""
          }
        }
      },
      selectedData: { id: "", name: "" },
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false
        },
        { text: "รายการแจ้งเตือนล่าสุด", value: "notification_msg" },
        { text: "ส่งครั้งล่าสุดเมื่อ", value: "null", sortable: false },
        { text: "ส่งอีกครั้ง", align: "right", value: "null", sortable: false },
        { text: "จัดการ", align: "right", value: "null", sortable: false }
      ]
    };
  },
  methods: {
    notifiSend: function() {
      let apiURL =
        "https://2542e143-54b2-497e-b818-91fbffc77642.pushnotifications.pusher.com/publish_api/v1/instances/2542e143-54b2-497e-b818-91fbffc77642/publishes";
      let setting = {
        headers: {
          Authorization: `Bearer 3D7D8EAFD50BA93F5060BF312202283DC82468E13353B1C92E8394D4BA8048DC`
        }
      };
      this.axios
        .post(apiURL, this.sendNotify, setting)
        .then(response => {
          Swal.fire({
            title: "ส่งการแจ้งเตือนเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.updateNotify();
          });
        })
        .catch(err => {
          Swal.fire({
            type: "error",
            title: "เกิดข้อผิดพลาด",
            showConfirmButton: false,
            timer: 1500
          });
          console.log(err);
        });
    },
    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/notification/datatable", setting)
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
    updateNotify: function() {
      let apiURL = "http://localhost:1337/notification/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.sendNotify.fcm.notification, setting)
        .then(response => {
          console.log("ok2");
          this.getData();
        })
        .catch(err => {
          Swal.fire({
            type: "error",
            title: "เกิดข้อผิดพลาด",
            showConfirmButton: false,
            timer: 1500
          });
          console.log(err);
        });
    },
    dialogSend: function() {
      Swal.fire({
        title: "ยืนยันการส่งการแจ้งเตือน",
        text: "ต้องการส่งการแจ้งเตือนนี้ซ้ำอีกรอบหรือไม่?",
        type: "warning",
        showCancelButton: true,

        cancelButtonText: "ยกเลิก",
        confirmButtonText: "ยืนยัน"
      }).then(result => {
        if (result.value) {
          this.notifiSend();
          console.log("ok1");
        }
      });
    },
    dialogDel: function() {
      Swal.fire({
        title: "ยืนยันการลบข้อมูล?",
        text: "ต้องการลบข้อมูลการแจ้งเตือนนี้หรือไม่?",
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
      let apiURL = "http://localhost:1337/notification/delete/";
      let getID = this.selectedData;
      this.axios
        .post(apiURL, getID, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "ลบข้อมูลเรียบร้อย",
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
      this.$router.push("/notification/send");
    }
  }
};
</script>