<template>
  <v-app>
    <v-card-title>
      <h1>รายงานผลการประเมินกิจกรรม</h1>
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

      <!-- <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon color="gray" v-on="on" @click="addButton">
            <v-icon>add</v-icon>
          </v-btn>
        </template>
        <span>เพิ่มข้อมูล</span>
      </v-tooltip>-->
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
        <v-list v-for="data  in years">
          <v-list-tile @click="(search = data,filterShow= 'ปีการศึกษา ' + data)">
            <v-list-tile-content>
              <v-list-tile-title>
                <b>ปีการศึกษา {{data}}</b>
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
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
          <v-icon>local_activity</v-icon>
        </td>
        <td class="text-xs-left">{{ props.item.event_name }}</td>
        <td class="text-xs-left">
          <b>{{ props.item.event_date | moment("DD MMMM YYYY")}}</b>
          <br>
          เวลา {{ props.item.event_start }} - {{ props.item.event_end }} น.
        </td>
        <td class="text-xs-left">
          <v-chip outline color="primary">ปีการศึกษา {{ props.item.event_year }}</v-chip>
        </td>
                <td class="text-xs-left">
          <v-chip outline color="primary">ภาคเรียนที่ {{ props.item.event_term }}</v-chip>
        </td>
        <td class="text-xs-right">
          <a
            :href="'//'+url +'/'+'?id='+props.item.id +'&eva_id='+props.item.eva_id "
            target="_blank"
          >
            <v-btn flat icon color="gray">
              <v-icon>notes</v-icon>
            </v-btn>
          </a>
          <!-- v-bind:to="{name: 'evaluationReportDetail', params: {eventID: props.item.id}}" -->
          <!-- <v-btn
            flat
            icon
            color="pink"
v-bind:to="{name: 'evaluationReportDetail', params: {eventID: props.item.id,evaID: props.item.eva_id}}" 
          >
            <v-icon>note</v-icon>
          </v-btn>-->
        </td>
      </template>
      <template v-slot:no-results>
        <v-alert :value="true" color="error" icon="warning">ไม่พบข้อมูล "{{ search }}" .</v-alert>
      </template>
      <template v-slot:footer>
        <td :colspan="headers.length">
          <strong>จำนวนกิจกรรมทั้งหมด {{data.length}} กิจจกรรม</strong>
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
      url: "localhost/coopApi",
      searchShow: true,
      filterShow: "แสดงทั้งหมด",
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
        { text: "", value: "event_year" },
        { text: "", value: "event_term" },
        // { text: "จำนวนผู้เข้าร่วม",value: "status" },

        { text: "ผลการรายงาน", align: "right", value: "null", sortable: false }
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
          console.log(err.response);
        });
    },

    addButton: function() {
      this.$router.push("/event/add");
    }
  },
  computed: {
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