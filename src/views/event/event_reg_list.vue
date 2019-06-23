<template>
  <v-app>
    <v-card-title>
      <h3>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
        รายชื่อผู้เข้าร่วมกิจกรรม {{ eventData.event_name }}
      </h3>
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
          <v-btn
            icon
            v-on="on"
            v-show="searchShow"
            @click="searchShow = !searchShow, search='',filterShow = 'แสดงทั้งหมด'"
            color="gray"
          >
            <v-icon>search</v-icon>
          </v-btn>
        </template>
        <span>ค้นหาข้อมูล</span>
      </v-tooltip>

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon color="gray" v-on="on" @click>
            <v-icon>vertical_align_bottom</v-icon>
          </v-btn>
        </template>
        <span>ส่งออกข้อมูล</span>
      </v-tooltip>
    </v-card-title>

    <v-menu offset-y max-height="80%">
      <template v-slot:activator="{ on }">
        <div>
          <v-layout align-center justify-end row fill-height>
            <v-flex xs12 sm4 v-on="on">
              <v-text-field
                v-if="filterShow == 'แสดงทั้งหมด'"
                prepend-icon="filter_list"
                v-model="filterShow"
              ></v-text-field>
              <v-text-field
                v-else
                prepend-icon="filter_list"
                v-model="filterShow"
                append-icon="close"
                @click:append="filterShow = 'แสดงทั้งหมด',search= null"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </div>
      </template>
      <div>
        <v-list v-for="majorlist, Faculty  in byTitile">
          <v-list-tile @click="(search = Faculty,filterShow=Faculty)">
            <v-list-tile-content>
              <v-list-tile-title>
                <b>{{Faculty}}</b>
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile
            @click="search = data.major_name, filterShow = data.major_name"
            v-for="data in majorlist"
          >
            <v-list-tile-avatar></v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title>{{data.major_name}}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-divider></v-divider>
        </v-list>
      </div>
    </v-menu>

    <v-data-table
      :headers="headers"
      :items="data"
      :search="search"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
    >
      <template v-slot:items="props">
        <td>
          <b>{{ props.item.user_id.user_id }}</b>
        </td>
        <td
          class="text-xs-left"
        >{{ props.item.user_id.prefix_name }} {{ props.item.user_id.first_name }} {{ props.item.user_id.last_name }}</td>
        <td class="text-xs-left">{{ props.item.major_id.major_name }}</td>

        <td class="text-xs-left">{{ props.item.faculty_id.faculty_name }}</td>

        <td class="text-xs-center">
          <v-chip v-if="props.item.user_id.role_name  == 'teacher'" outline color="blue">คณาจารย์</v-chip>
          <v-chip v-else outline color="blue">นักศึกษา</v-chip>
        </td>

        <td class="text-xs-center">
          <v-icon v-if="props.item.eva_status == 1" color="green">done</v-icon>
          <v-icon v-else color="red">clear</v-icon>
        </td>
        <!-- <td class="text-xs-center">
          <v-chip v-if="props.item.status == 1" outline color="green">เข้าร่วมแล้ว</v-chip>
          <v-chip v-else outline color="red">รอการยืนยัน</v-chip>
        </td>-->
        <td class="text-xs-right">
          <!-- <v-btn flat icon color="blue"
          
                    v-bind:to="{name: 'facultyMajorDetail', params: {majorID: props.item.id}}">
            <v-icon>create</v-icon>

            
          </v-btn>-->
          <!-- <v-btn v-show="props.item.status == 0"
            flat
            color="blue"
            @click="(selectedData.id = props.item.id,selectedData.status = 1),AcceptData() "
          >
            <v-icon>how_to_reg</v-icon>ยืนยันการเข้าร่วม
          </v-btn>-->

          <v-btn
            flat
            icon
            color="pink"
            @click="(selectedData.id = props.item.id,selectedData.name = props.item.user_id.first_name +' ' +props.item.user_id.last_name),dialogDel() "
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
          <strong>จำนวนผู้เข้าร่วมทั้งหมด {{data.length}} คน</strong>
        </td>
      </template>
    </v-data-table>
    {{majorData}}
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getData();
    this.getEventData();
    this.getMajorData();
  },
  data() {
    return {
      search: "",
      searchShow: true,
      eventData: "",
      data: [],
      majorData: [],
      facultyData: "",
      filterShow: "แสดงทั้งหมด",
      rowsPerPageItems: [9, 18, 27, 32, 99, 999],
      pagination: {
        rowsPerPage: 9
      },
      selectedData: { id: "", name: "", status: "" },
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
          value: "user_id.last_name"
        },
        { text: "รายชื่อ", value: "user_id.first_name" },
        { text: "สาขา", value: "major_id.major_name" },
        { text: "คณะ", value: "faculty_id.faculty_name" },
        { text: "ตำแหน่ง", align: "center", value: "user_id.role_name" },

        { text: "การประเมิน", align: "center", value: "eva_status" },
        // { text: "สถานะการเข้าร่วม", value: "status" },
        {
          text: "จัดการ",
          align: "right",
          value: "user_id.user_id",
          sortable: false
        }
      ]
    };
  },
  methods: {
    getMajorData: function() {
      let apiURL = "http://localhost:1337/major/datatable";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL, setting)
        .then(
          response => (
            (this.majorData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getData: function() {
      let apiURL = "http://localhost:1337/event/report/";
      let getID = this.$route.params.eventID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.data = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getEventData: function() {
      let apiURL = "http://localhost:1337/event/";
      let getID = this.$route.params.eventID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.eventData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
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
      let apiURL = "http://localhost:1337/event/report/delete";
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
    AcceptData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      let apiURL = "http://localhost:1337/event/report/accept";
      let getID = this.selectedData;
      this.axios
        .post(apiURL, getID, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "ยืนยันการเข้าร่วมเรียบร้อย",
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
    backButton: function() {
      this.$router.push("/event_report");
    }
  },
  computed: {
    byTitile() {
      return this.majorData.reduce((acc, data) => {
        (acc[data.faculty_id.faculty_name] =
          acc[data.faculty_id.faculty_name] || []).push({
          major_name: data.major_name
        });

        return acc;
      }, {});
    }
  }
};
</script>