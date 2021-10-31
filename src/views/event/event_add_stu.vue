<template>
  <v-app>
    
    <v-card-title>
            <v-toolbar-items>
        <v-btn flat icon @click="$router.go(-1)">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <h1>เพิ่มนักศึกษาเข้าร่วมกิจกรรม </h1>
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
    </v-card-title>

    <v-menu offset-y max-height="80%">
      <template v-slot:activator="{ on }">
        <div>
          <v-layout align-center justify-end row fill-height>
            <div>
              <v-select
                append-icon="close"
                v-model="year"
                :items="years"
                label="ปีการศึกษา"
                v-on:change="getData"
                @click:append="year= null"
              />
            </div>
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
    <v-card-title>
      <h2>รายชื่อทั้งหมด</h2>
    </v-card-title>

    <!-- <div v-if="year == null" class="headline text-xs-center">
        <br><br><br>
        <v-icon style="  font-size: 99pt;">error_outline</v-icon>
        <br />โปรดเลือกปีการศึกษาก่อน
    </div>-->

    <v-data-table
      :headers="headers"
      :items="data"
      :search="search"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
    >
      <template v-slot:items="props">
        <td>{{ props.index +1 }}</td>
        <td class="text-xs-left">{{ props.item.user_id }}</td>
        <td
          class="text-xs-left"
        >{{ props.item.prefix_name }} {{ props.item.first_name }} {{ props.item.last_name }}</td>

        <td class="text-xs-left">{{ props.item.major_name }}</td>

        <td class="text-xs-left">{{ props.item.faculty_name }}</td>
        <td class="text-xs-right">
          <v-btn
            v-if="props.item.checkEvent == 0"
            flat
            icon
            color="blue"
            @click="(form.user_id = props.item.id ,form.faculty_id = props.item.faculty_id,form.major_id = props.item.major_id,form.event_year = eventData.event_year,form.event_term = eventData.event_term),dialogJoin() "
          >
            <v-icon>person_add</v-icon>
          </v-btn>
          <v-btn
            v-else
            flat
            icon
            color="green"
            @click="(selectedData.id = props.item.regis_id,selectedData.name = props.item.first_name +' ' +props.item.last_name),dialogDel() "
          >
            <v-icon>done</v-icon>
          </v-btn>
        </td>
      </template>
      <template v-slot:no-results>
        <v-alert :value="true" color="error" icon="warning">ไม่พบข้อมูล "{{ search }}" .</v-alert>
      </template>
      <!-- <template v-slot:footer>
        <td :colspan="headers.length">
          <strong>จำนวนนักศึกษาทั้งหมด {{data.length}} คน</strong>
        </td>
      </template>-->
    </v-data-table>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getData();
    this.getMajorData();
    this.getEventData();
  },
  data() {
    return {
      search: "",
      year: "",
      searchShow: true,
      facultyData: "",
      filterShow: "แสดงทั้งหมด",
      majorData: [],
      data: [],
      eventData: [],
      rowsPerPageItems: [9, 18, 27, 32, 99],
      pagination: {
        rowsPerPage: 9
      },
      form: {
        event_id: this.$route.params.eventID,
        user_id: "",
        user_role: "student",
        faculty_id: "",
        major_id: "",
        event_year: "",
        event_term: ""
      },
      selectedData: { id: "", name: "", status: "" },
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
          value: "last_name"
        },
        { text: "รหัสนักศึกษา", value: "user_id" },
        { text: "ชื่อ - นามสกุล", value: "first_name" },

        { text: "สาขา", value: "major_name" },
        { text: "คณะ", value: "faculty_name" },

        { text: "เข้าร่วม", align: "right", value: "null", sortable: false }
      ]
    };
  },
  methods: {
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
      let apiURL = "http://localhost/coopApi/event_add_stu.php?event_id=";
      let getID = this.$route.params.eventID;
      let year = "&year=";
      let getYear = this.year;
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + year + getYear, setting)
        .then(
          response => ((this.data = response.data), (this.isLoading = false))
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
    dialogJoin: function() {
      setTimeout(this.JoinEvent, 2);
    },
    dialogDel: function() {
      setTimeout(this.deleteData, 2);
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
            title: "ยกเลิกการเข้าร่วม " + this.selectedData.name + " เรียบร้อย",
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
    JoinEvent: function() {
      let apiURL = "http://localhost:1337/eventApp/join";
      this.axios
        .post(apiURL, this.form)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            text: "เข้าร่วมเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 2000
          }).then(result => {
            console.log("ok3");
                this.getData();
            console.log(response);
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
    backButton: function() {
      this.$router.push({
        name: "EventRegList",
        params: { eventID: this.$route.params.eventID }
      });
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
    },
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