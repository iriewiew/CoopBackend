<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขข้อมูลกิจกรรม</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn flat icon @click="submit('form')">
          <v-icon>save</v-icon>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>

    <v-card-title>
      <h1></h1>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-form>
      <v-container>
        <v-layout row wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.event_name"
              label="ชื่อกิจกรรม"
              v-validate="'required|max:255'"
              :counter="255"
              :error-messages="errors.collect('event_name')"
              data-vv-name="event_name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md2>
            <v-text-field
              v-model="form.event_date"
              label="วันที่จัดกิจกรรม"
              v-validate="'required|'"
              :error-messages="errors.collect('event_date')"
              data-vv-name="event_date"
              type="date"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md2>
            <v-text-field
              v-model="form.event_start"
              label="เวลาเริ่มกิจกรรม"
              v-validate="'required|'"
              :error-messages="errors.collect('event_start')"
              data-vv-name="event_start"
              type="time"
              suffix="น."
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md2>
            <v-text-field
              v-model="form.event_end"
              label="เวลาสิ้นสุดกิจกรรม"
              v-validate="'required|'"
              :error-messages="errors.collect('event_end')"
              data-vv-name="event_end"
              type="time"
              suffix="น."
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md3>
            <v-select
              v-model="form.event_year"
              :items="years"
              label="ปีการศึกษา"
              v-validate="'required'"
              :error-messages="errors.collect('event_year')"
              data-vv-name="event_year"
              required
            />
          </v-flex>
          <v-flex xs12 md3>
            <v-select
              v-model="form.event_term"
              :items="term"
              item-text="name"
              item-value="id"
              label="ภาคเรียน"
              v-validate="'required'"
              :error-messages="errors.collect('event_term')"
              data-vv-name="event_term"
              required
            />
          </v-flex>

          <v-flex xs12 md12>
            <v-text-field
              v-model="form.event_place"
              label="สถานที่จัดกิจกรรม"
              v-validate="'required|'"
              :error-messages="errors.collect('event_place')"
              data-vv-name="event_place"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12>
            <v-textarea
              v-model="form.event_detail"
              label="รายละเอียดกิจกรรม"
              value="event_detail"
              v-validate="'required|'"
              :error-messages="errors.collect('event_detail')"
              data-vv-name="event_detail"
              required
            ></v-textarea>
          </v-flex>
          <br />
          <!-- <v-btn @click="clear">clear</v-btn> -->
          <v-toolbar flat>
            <v-card-title>
              <h1>การตั้งค่ากิจกรรม</h1>
              <v-spacer></v-spacer>
            </v-card-title>
            <v-spacer></v-spacer>
          </v-toolbar>

          <v-flex xs6>
            <v-text-field
              v-model="form.event_limit"
              label="จำนวนผู้เข้าร่วม"
              suffix="คน"
              v-validate="'required|numeric'"
              :error-messages="errors.collect('event_limit')"
              data-vv-name="event_limit"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs6>
            <v-text-field
              v-model="form.eva_achieve_point"
              label="ผลบรรลุการเข้าร่วม (ร้อยละ)"
              v-validate="'required|max:3|numeric'"
              :counter="3"
              :error-messages="errors.collect('eva_achieve_point')"
              data-vv-name="eva_achieve_point"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs6>
            <v-select
              item-text="eva_name"
              item-value="id"
              v-model="form.eva_id"
              :items="evaData"
              label="แบบการประเมิน"
              v-validate="'required'"
              :error-messages="errors.collect('eva_data')"
              data-vv-name="eva_data"
              required
            />
          </v-flex>
          <v-flex 6>
            <v-select
              v-if="form.event_date == form.todayDate"
              item-text="name"
              item-value="id"
              v-model="form.event_eva_status"
              :items="eva_title"
              label="สถานะการประเมิน"
              v-validate="'required'"
              :error-messages="errors.collect('event_eva_status')"
              data-vv-name="event_eva_status"
              required
            />

            <v-select
              v-else
              item-text="name"
              item-value="id"
              v-model="form.event_eva_status"
              
              label="กิจกรรมสิ้นสุดแล้ว"
              v-validate="'required'"
              :error-messages="errors.collect('event_eva_status')"
              data-vv-name="event_eva_status"
              disabled
            />
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
  </v-app>
</template>
<script>
import axios from "axios";
export default {
  mounted() {
    this.getEventData();
    this.getData();
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {

      title: [
        { name: "ปิดการลงทะเบียน", id: 0 },
        { name: "เปิดให้ลงทะเบียน", id: 1 }
      ],
      eva_title: [
        { name: "ปิดการประเมินกิจกรรม", id: 0 },
        { name: "เปิดให้ประเมินกิจกรรม", id: 1 }
      ],
      term: [
        { name: "ภาคเรียนที่ 1", id: 1 },
        { name: "ภาคเรียนที่ 2", id: 2 },
        { name: "ภาคเรียนที่ 3", id: 3 }
      ],
      type: [
        { name: "เฉพาะนักศึกษา", id: "student" },
        { name: "เฉพาะคณาจารย์", id: "teacher" },
        { name: "เข้าร่วมได้ทั้งนักศึกษาและคณาจารย์", id: "all" }
      ],
      evaData: "",
      data: "",
      form: "",
      dictionary: {
        custom: {
          event_name: {
            required: () => "โปรดกรอกชื่อกิจกรรม",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          event_place: {
            required: () => "โปรดกรอกสถานที่จัดกิจกรรม",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          event_start: {
            required: () => "โปรดกรอกเวลาเริ่มต้นกิจกรรม"
            // custom messages
          },
          event_date: {
            required: () => "โปรดกรอกวันที่จัดกิจกรรม"
            // custom messages
          },
          event_end: {
            required: () => "โปรดกรอกเวลาสิ้นสุดกิจกรรม"
            // custom messages
          },
          event_detail: {
            required: () => "โปรดระบุรายละเอียดของกิจกรรม"
            // custom messages
          },
          event_role: {
            required: () => "โปรดเลือกสิทธ์การเข้าร่วมของกิจกรรม"
            // custom messages
          },
          event_year: {
            required: () => "โปรดเลือกปีการศึกษา"
            // custom messages
          },
          event_term: {
            required: () => "โปรดเลือกภาคเรียน"
            // custom messages
          },
          event_eva_status: {
            required: () => "โปรดเลือกสถานะของการประเมินกิจกรรม"
            // custom messages
          },
          status: {
            required: () => "โปรดเลือกสถานะของกิจกรรม"
            // custom messages
          },
          eva_data: {
            required: () => "โปรดเลือกแบบประเมิน"
            // custom messages
          },
          event_limit: {
            required: () => "โปรดกรอกจำนวนผู้เข้าร่วม",
            numeric: "จำนวนผู้เข้าร่วมควรเป็นตัวเลขเท่านั้น",
            min: "ผู้ควรมีตั้งแต่ 1 คนขึ้นไป"
            // custom messages
          },
          eva_achieve_point: {
            required: () => "โปรดกรอกคะแนน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            numeric: "ตัวเลขเท่านั้น",
            max: "คะแนนไม่ควรเกิน 3 หลัก"
            // custom messages
          }
        }
      }
    };
  },

  methods: {
    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/evaluation/datatable", setting)
        .then(
          response => (
            (this.evaData = response.data.data), (this.isLoading = false)
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
            (this.form = response.data.data[0]), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/event/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.$router.push("/event");
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
    submit(form) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.saveData();
          return;
        }
      });
    },
    backButton: function() {
      this.$router.push("/event");
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
<style scoped>
</style>
