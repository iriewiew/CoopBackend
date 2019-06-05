<template>
  <v-app>
    <v-card-title>
      <h3>    <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn> รายชื่อสาขาวิชาทั้งหมดของคณะ {{ data.faculty_name }}</h3>
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
          <v-btn icon color="gray" v-on="on" @click="addMajorButton">
            <v-icon>add</v-icon>
          </v-btn>
        </template>
        <span>เพิ่มข้อมูล</span>
      </v-tooltip>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="majorData"
      :search="search"
      :rows-per-page-items="rowsPerPageItems"
      :pagination.sync="pagination"
    >
      <template v-slot:items="props">
        <td>{{ props.index +1 }}</td>
        <td class="text-xs-left">{{ props.item.major_name }}</td>
        <td class="text-xs-right">
          <v-btn flat icon color="blue"
          
                    v-bind:to="{name: 'facultyMajorDetail', params: {majorID: props.item.id}}">
            <v-icon>create</v-icon>

            
          </v-btn>
          <v-btn
            flat
            icon
            color="pink"
            @click="(selectedData.id = props.item.id,selectedData.name = props.item.major_name),dialogDel() "
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
          <strong>จำนวนสาขาทั้งหมด {{majorData.length}} สาขา</strong>
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
    this.getMajorData();
  },
  data() {
    return {
      search: "",
      searchShow: true,
      data: [],
      majorData: [],
      rowsPerPageItems: [9, 18, 27, 32, 99],
      pagination: {
        rowsPerPage: 9
      },
      selectedData: { id: "", name: "" },
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false
        },
        { text: "รายชื่อสาขา", value: "major_name" },

        { text: "จัดการ", align: "right", value: "null", sortable: false }
      ]
    };
  },
  methods: {
    getData: function() {
      let apiURL = "http://localhost:1337/faculty/";
      let getID = this.$route.params.facultyID;
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
    getMajorData: function() {
      let apiURL = "http://localhost:1337/faculty/major/";
      let getID = this.$route.params.facultyID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.majorData = response.data.data), (this.isLoading = false)
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
      let apiURL = "http://localhost:1337/major/delete/";
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
            this.getMajorData();
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
      this.$router.push("/faculty");
    },
    addMajorButton: function() {
      this.$router.push({
        name: "facultyMajorAdd",
        params: { facultyID: this.$route.params.facultyID }
      });
    }
  }
};
</script>