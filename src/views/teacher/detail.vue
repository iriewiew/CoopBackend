<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขข้อมูลคณาจารย์</h1>
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
          <v-flex xs12 sm12 md12>
            <v-text-field
              v-model="form.user_id"
              label="รหัสตำแหน่ง"
              v-validate="'required|max:5|numeric|min:5'"
              :counter="5"
              :error-messages="errors.collect('user_id')"
              data-vv-name="user_id"
              required
              disabled
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md2>
            <v-select
              item-text="title"
              item-value="title"
              v-model="form.prefix_name"
              :items="prefix_name"
              label="คำนำหน้า"
              v-validate="'required'"
              :error-messages="errors.collect('selectPrefix')"
              data-vv-name="selectPrefix"
              required
            />
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.first_name"
              label="ชื่อ"
              v-validate="'required'"
              :error-messages="errors.collect('first_name')"
              data-vv-name="first_name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.last_name"
              label="นามสกุล"
              v-validate="'required'"
              :error-messages="errors.collect('last_name')"
              data-vv-name="last_name"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md12>
            <v-select
              item-text="faculty_name"
              item-value="id"
              v-model="form.faculty_name"
              :items="facultyData"
              label="สังกัดคณะ"
              v-validate="'required'"
              :error-messages="errors.collect('selectfaculty')"
              data-vv-name="selectfaculty"
              required
            />
          </v-flex>


          <!-- <v-flex xs12 md12>
            <v-text-field
              v-model="form.email"
              label="อีเมล"
              v-validate="'required|email'"
              :error-messages="errors.collect('email')"
              data-vv-name="email"
              required
              type="email"
            ></v-text-field>
          </v-flex> -->
          <!-- <v-btn @click="clear">clear</v-btn> -->
        </v-layout>
      </v-container>
    </v-form>
  </v-app>
</template>
<script>
import axios from "axios";

export default {
  mounted() {
    this.getTeacherData();
    this.getData();
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      viewSelect: true,
      facultyData: "",
      majorFacultyData: "",
      majorData: [],
      prefix_name: [
        {
          title: "นาย"
        },
        {
          title: "นาง"
        },
        {
          title: "นางสาว"
        }
      ],
      form: {
        user_id: "",
        prefix_name: "",
        first_name: "",
        last_name: "",
        role_name: "teacher",
        major_name: "",
        faculty_name: "",
        email: ""
      },
      dictionary: {
        attributes: {
          email: "E-mail Address"
          // custom attributes
        },
        custom: {
          user_id: {
            required: () => "โปรดกรอกรหัสตำแหน่ง",
            min: "รหัสตำแหน่งควรเป็นตัวเลขจำนวน 5 หลัก",
            max: "รหัสตำแหน่งควรเป็นตัวเลขไม่เกิน 5 หลัก",
            numeric: "รหัสตำแหน่งควรเป็นตัวเลขเท่านั้น"
            // custom messages
          },
          first_name: {
            required: () => "โปรดกรอกชื่อ",
            alpha: "ไม่ควร"

            // custom messages
          },
          last_name: {
            required: () => "โปรดกรอกนามสกุล"

            // custom messages
          },
          selectPrefix: {
            required: "โปรดเลือกคำนำหน้า"
          },
          selectfaculty: {
            required: "โปรดเลือกคณะ"
          },
          selectMajor: {
            required: "โปรดเลือกสาขาวิชา"
          },
          email: {
            required: () => "โปรดกรอกอีเมล",
            email: "รูปแบบอีเมลไม่ถูกต้อง"
          }
        }
      }
    };
  },
  methods: {
    loadMajorView() {
      setTimeout(() => {
        this.getMajorData();
      }, 400);
    },
    getTeacherData: function() {
      let apiURL = "http://localhost:1337/member/";
      let getID = this.$route.params.teacherID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.form = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/member/update";
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
            this.$router.push("/teacher");
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
    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/faculty/datatable", setting)
        .then(
          response => (
            (this.facultyData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
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
      this.$router.push("/teacher");
    }
  }
};
</script>
<style scoped>
</style>
