<template>
  <v-app>
    <v-card-title>
      <h1>จัดการผู้ใช้งานระบบ</h1>
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
        <td>{{ props.index +1 }}</td>
        <td
          class="text-xs-left"
        >{{ props.item.name }}</td>

        <td class="text-xs-left">{{props.item.username}}</td>
        <td class="text-xs-left">{{props.item.status}}</td>
        <td class="text-xs-right">
          <v-btn
            flat
            icon
            color="blue"
            v-bind:to="{name: 'usermanageEdit', params: {userID: props.item.id}}"
          >
            <v-icon>create</v-icon>
          </v-btn>
          
          <v-btn
            flat
            icon
            color="pink"
            @click="(selectedData.id = props.item.id,selectedData.name = props.item.name,selectedData.username = props.item.username),dialogDel() "
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
          <strong>บัญชีผู้ใช้งานทั้งหมด {{data.length}} บัญชี</strong>
        </td>
      </template>
    </v-data-table>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">เพิ่มด้านของแบบประเมิน</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    label="ชื่อด้าน"
                    v-model="form.eva_titile_name"
                    v-validate="'required|max:255'"
                    :counter="255"
                    :error-messages="errors.collect('titile_name')"
                    data-vv-name="titile_name"
                    required
                  ></v-text-field>
                  <v-text-field
                    label="ผลบรรลุ (ร้อยละ)"
                    v-model="form.eva_titile_achieve_point"
                    v-validate="'required|max:3|numeric'"
                    :counter="3"
                    :error-messages="errors.collect('eva_titile_achieve_point')"
                    data-vv-name="eva_titile_achieve_point"
                    required
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              round
              flat
              @click="(form.eva_titile_name='',form.eva_titile_achieve_point=''),dialog = false,$validator.reset();"
            >ยกเลิก</v-btn>
            <!-- <v-btn color="green" flat @click="saveData()">บันทึก</v-btn> -->

            <v-btn v-if="!form.eva_titile_name" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else-if="!form.eva_titile_achieve_point" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else color="primary" round flat @click="saveData">
              <span>บันทึก</span>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

    </v-layout>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.$validator.localize("th", this.dictionary);
    this.getData();
    this.getTitileData();
    this.getListData();
  },
  data() {
    return {
      search: "",
      searchShow: true,
      dialog: false,
      dialogEdit: false,
      data: [],
      form: {
        id: "",
        eva_titile_name: "",
        eva_titile_achieve_point: "",
        eva_id: this.$route.params.evaluationID
      },
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
          value: ""
        },
        { text: "ชื่อผู้ใช้", value: "name" },

        { text: "ชื่อบัญชีผู้ใช้", value: "username" },
        { text: "สถานะ", value: "status" },
        { text: "จัดการ", align: "right", value: "null", sortable: false }
      ],
      rowsPerPageItems: [9, 18, 27, 32, 99],
      pagination: {
        rowsPerPage: 9
      },
      selectedData: { id: "", name: "" ,username:"" },
      dictionary: {
        custom: {
          titile_name: {
            required: () => "โปรดกรอกข้อมูลให้ครบถ้วน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร"

            // custom messages
          },
          list_name: {
            required: () => "โปรดกรอกข้อมูลให้ครบถ้วน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร"

            // custom messages
          },
          eva_point: {
            required: () => "โปรดกรอกคะแนน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            numeric: "ตัวเลขเท่านั้น",
            max: "คะแนนไม่ควรเกิน 3 หลัก"
            // custom messages
          },
          eva_titile_achieve_point: {
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
      axios
        .get("http://localhost:1337/api/users/datatable", {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(
          response => (
            (this.data = response.data.data),
            (this.loading = false),
            (this.nodata = "ไม่มีข้อมูล")
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

    saveEditData: function() {
      let apiURL = "http://localhost:1337/evaluation/titile/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          this.dialogEdit = false;

          console.log("ok2");
          Swal.fire({
            title: "แก้ไขเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.form.eva_titile_name = "";
            this.form.eva_titile_achieve_point = "";

            this.getData();
            this.getTitileData();
            this.getListData();
            this.$validator.reset();
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
          this.dialogEdit = false;
          this.$validator.reset();
        });
    },
    submit(form) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.saveNewUserData();
          return;
        }
      });
    },
    dialogDel: function() {
      Swal.fire({
        title: "ยืนยันการลบข้อมูล?",
        text:
          "ต้องการลบบัญชีผู้ใช้ " +
          this.selectedData.username +
          " " +
          "(" +
          this.selectedData.name +
          ")" +
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
      this.axios
        .post("http://localhost:1337/api/users/delete", this.selectedData, {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(response => {
          (this.loading = true), (this.nodata = "กำลังโหลด..");
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
      this.$router.push("/usermanage/add");
    },

    backButton: function() {
      this.$router.push("/evaluation");
    }
  }
};
</script>