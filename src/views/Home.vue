<template>
  <v-app>
    <v-container fluid grid-list-md>
      <span class="display-3">ยินดีต้อนรับ'</span>
      <span class="display-2">{{$store.state.getApiData.name}}</span>
      <v-layout row wrap>
        <v-flex d-flex xs12 sm6 md6>
          <v-card class="mx-auto" color="pink lighten-2" dark max-width="540">
            <v-card-title>
              <v-icon large left>date_range</v-icon>
              <span class="title font-weight-light">กิจกรรมล่าสุด</span>
            </v-card-title>
            <v-list-tile-title v-show="!eventData.length">
              <center>ไม่พบข้อมูลกิจกรรมล่าสุด</center>
            </v-list-tile-title>

            <v-list-tile avatar ripple v-for="event in eventData.slice(0, 4)">
              <v-list-tile-avatar>
                <v-icon>notes</v-icon>
              </v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title>
                  <b>กิจกรรม {{ event.event_name }}</b>
                </v-list-tile-title>
                <v-list-tile-sub-title>วันที่ {{ event.event_date | moment("DD MMMM YYYY") }}</v-list-tile-sub-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <v-list-tile-action-text>
                  <v-icon v-if="event.status == 0" color="grey lighten-1">fiber_manual_record</v-icon>
                  <v-icon v-else color="green">fiber_manual_record</v-icon>
                </v-list-tile-action-text>
              </v-list-tile-action>
            </v-list-tile>

            <v-card-actions>
              <v-list-tile class="grow">
                <v-list-tile-content>
                  <br>
                </v-list-tile-content>

                <v-layout align-center justify-end>
                  <v-btn flat to="/event">จัดการ</v-btn>
                </v-layout>
              </v-list-tile>
            </v-card-actions>
          </v-card>
        </v-flex>
        <v-flex d-flex xs12 sm6 md3>
          <v-layout row wrap>
            <v-flex d-flex>
              <v-layout row wrap>
                <v-flex d-flex xs12>
                  <v-card color="blue darken-1" dark>
                    <v-card-title>
                      <v-icon large left>calendar_today</v-icon>
                      <span class="title font-weight-light">กิจกรรมทั้งหมด</span>
                    </v-card-title>
                    <center>
                      <span class="display-3">{{eventData.length}}</span>
                      <br>กิจกรรม
                    </center>
                    <br>
                    <v-card-title>
                      <v-icon large left>how_to_reg</v-icon>
                      <span class="title font-weight-light">จำนวนการเข้าร่วม</span>
                    </v-card-title>
                    <center>
                      <span class="display-3">{{eventJoinData.length}}</span>
                      <br>ครั้ง
                    </center>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex d-flex xs12 sm6 md3>
          <v-layout row wrap>
            <v-flex d-flex>
              <v-layout row wrap>
                <v-flex d-flex xs12>
                  <v-card color="green" dark to="/teacher">
                    <v-card-title>
                      <v-icon large left>face</v-icon>
                      <span class="title font-weight-light">คณาจารย์ทั้งหมด</span>
                    </v-card-title>
                    <center>
                      <span class="display-3">{{teacherData.length}}</span>
                      <br>ท่าน
                    </center>
                    <br>
                  </v-card>
                </v-flex>
                <v-flex d-flex xs12>
                  <v-card color="teal " dark to="/student">
                    <v-card-title>
                      <v-icon large left>face</v-icon>
                      <span class="title font-weight-light">นักศึกษาทั้งหมด</span>
                    </v-card-title>
                    <center>
                      <span class="display-3">{{studentData.length}}</span>
                      <br>คน
                    </center>
                    <br>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getEventData();
    this.getStudentData();
    this.getTeacherData();
    this.getEventJoinData();
  },
  data() {
    return {
      eventData: "",
      studentData: "",
      teacherData: "",
      eventJoinData: ""
    };
  },

  methods: {
    getEventJoinData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/event/report/alldata", setting)
        .then(
          response => (
            (this.eventJoinData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getEventData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/event/datatable", setting)
        .then(
          response => (
            (this.eventData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getStudentData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/student/datatable", setting)
        .then(
          response => (
            (this.studentData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getTeacherData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/teacher/datatable", setting)
        .then(
          response => (
            (this.teacherData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    }
  }
};
</script>
