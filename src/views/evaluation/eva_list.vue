<template>
  <v-app>
    <v-card-title>
      <h2>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
        ข้อประเมินของ {{ data.eva_name }}
      </h2>
      <v-spacer></v-spacer>
      <v-btn flat @click="dialog = true">
        <v-icon>add</v-icon>เพิ่มด้านของแบบประเมิน
      </v-btn>
    </v-card-title>

    <v-container>
      <div>
        <br>
        <h2>ด้านของแบบประเมิน</h2>
        <br>
        <v-layout row>
          <v-flex xs12>
            <span v-if="titileData.length === 0">
              ไม่มีข้อมูล
              <b>"ด้านของแบบประเมิน"</b> โปรดทำการ
              <v-btn small flat @click="dialog = true">
                <v-icon>add</v-icon>เพิ่มด้านของแบบประเมิน
              </v-btn>
            </span>

            <div v-for="titile ,index in titileData">
              <v-list-tile>
                <v-list-tile-avatar>
                  <v-avatar color="blue lighten-4"    size="36px">
                    <span class=" headline">{{index+1}}</span>
                  </v-avatar>
                </v-list-tile-avatar>

                <v-list-tile-content>
                  <v-list-tile-title>{{titile.eva_titile_name}}</v-list-tile-title>
                  <v-list-tile-sub-title>ผลบรรลุ ร้อยละ {{titile.eva_titile_achieve_point}} คะแนน</v-list-tile-sub-title>
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-btn icon>
                    <v-icon
                      color="blue"
                      @click="(form.id = titile.id ,form.eva_titile_name = titile.eva_titile_name,form.eva_titile_achieve_point = titile.eva_titile_achieve_point),dialogEdit = true"
                    >create</v-icon>
                  </v-btn>
                </v-list-tile-action>
                <v-btn icon>
                  <v-icon
                    color="red"
                    @click="(selectedData.id = titile.id,selectedData.name = titile.eva_titile_name),dialogDel() "
                  >delete_outline</v-icon>
                </v-btn>
                <v-list-tile-action>
                  <v-btn
                    flat
                    @click="(formList.eva_titile_name_id = titile.id,selectedData.name = titile.eva_titile_name),(dialogList = true)"
                  >
                    <v-icon>add</v-icon>เพิ่มข้อประเมิน
                  </v-btn>
                </v-list-tile-action>
              </v-list-tile>
              <v-divider></v-divider>
            </div>
          </v-flex>
        </v-layout>
        <br>
        <br>
        <div v-if="titileData.length > 0">
          <h2>ข้อแบบประเมิน</h2>
          <br>
          <span v-if="listData.length === 0">
            ไม่มีข้อมูล
            <b>"ข้อแบบประเมิน"</b> โปรดทำการ
            <b>+ เพิ่มข้อประเมิน</b>
          </span>

          <v-layout row>
            <v-flex xs12>
              <div v-for="list, Titile ,index in byTitile">
                <v-toolbar color="pink lighten-5" flat>
                  <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->

                  <v-toolbar-title>{{index+1}}. {{Titile}}</v-toolbar-title>

                  <v-spacer></v-spacer>
                </v-toolbar>

                <v-list two-line>
                  <template>
                    <div v-for="data,index in list">
                      <v-list-tile>
                                    <v-list-tile-avatar>
                  <v-avatar color="pink lighten-4"    size="36px">
                    <span class=" headline">{{index+1}}</span>
                  </v-avatar>
                </v-list-tile-avatar>

                        <v-list-tile-content>
                          <v-list-tile-title>{{data.eva_list}}</v-list-tile-title>
                          <v-list-tile-sub-title>{{data.eva_point}} คะแนน</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <v-btn icon>
                            <v-icon
                              color="blue"
                              @click="(formList.id = data.id ,formList.eva_list_name = data.eva_list,formList.eva_point = data.eva_point),dialogListEdit = true"
                            >create</v-icon>
                          </v-btn>
                        </v-list-tile-action>
                        <v-list-tile-action>
                          <v-btn
                            icon
                            @click="(selectedData.id = data.id,selectedData.name = data.eva_list),dialogListDel() "
                          >
                            <v-icon color="red">delete_outline</v-icon>
                          </v-btn>
                        </v-list-tile-action>
                      </v-list-tile>
                      <v-divider></v-divider>
                    </div>
                    <!-- <v-list-tile>
                  <v-list-tile-content></v-list-tile-content>
                  <v-list-tile-action>
                    <v-btn flat>
                      <v-icon>add</v-icon>เพิ่มข้อประเมิน
                    </v-btn>
                  </v-list-tile-action>
                    </v-list-tile>-->
                  </template>
                </v-list>

                <br>
              </div>
            </v-flex>
          </v-layout>
        </div>
      </div>
    </v-container>
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

      <v-dialog v-model="dialogEdit" persistent max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">แก้ไขด้านของแบบประเมิน</span>
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
              @click="(form.eva_titile_name='',form.eva_titile_achieve_point=''),dialogEdit = false,$validator.reset();"
            >ยกเลิก</v-btn>
            <!-- <v-btn color="green" flat @click="saveData()">บันทึก</v-btn> -->

            <v-btn v-if="!form.eva_titile_name" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else-if="!form.eva_titile_achieve_point" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else color="primary" round flat @click="saveEditData">
              <span>บันทึก</span>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogList" persistent max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">เพิ่มข้อประเมิน</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    label="ชื่อรายการ"
                    v-model="formList.eva_list_name"
                    v-validate="'required|max:255'"
                    :counter="255"
                    :error-messages="errors.collect('list_name')"
                    data-vv-name="list_name"
                    required
                  ></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-text-field
                    label="คะแนน"
                    v-model="formList.eva_point"
                    v-validate="'required|max:3|numeric'"
                    :counter="3"
                    :error-messages="errors.collect('eva_point')"
                    data-vv-name="eva_point"
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
              @click="(formList.eva_list_name= '',formList.eva_point=''),dialogList = false,$validator.reset();"
            >ยกเลิก</v-btn>
            <!-- <v-btn color="green" flat @click="saveDataList">บันทึก</v-btn> -->

            <v-btn v-if="!formList.eva_list_name" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else-if="!formList.eva_point" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else color="primary" round flat @click="saveDataList">
              <span>บันทึก</span>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogListEdit" persistent max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">แก้ไขข้อประเมิน</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    label="ชื่อรายการ"
                    v-model="formList.eva_list_name"
                    v-validate="'required|max:255'"
                    :counter="255"
                    :error-messages="errors.collect('list_name')"
                    data-vv-name="list_name"
                    required
                  ></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-text-field
                    label="คะแนน"
                    v-model="formList.eva_point"
                    v-validate="'required|max:3|numeric'"
                    :counter="3"
                    :error-messages="errors.collect('eva_point')"
                    data-vv-name="eva_point"
                    required
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <!-- {{formList}} -->
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              round
              flat
              @click="(formList.eva_list_name= '',formList.eva_point=''),dialogListEdit = false,$validator.reset();"
            >ยกเลิก</v-btn>
            <!-- <v-btn color="green" flat @click="saveDataList">บันทึก</v-btn> -->

            <v-btn v-if="!formList.eva_list_name" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else-if="!formList.eva_point" color="primary" round flat disabled>
              <span>บันทึก</span>
            </v-btn>
            <v-btn v-else color="primary" round flat @click="saveDataEditList">
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
      dialog: false,
      dialogList: false,
      dialogEdit: false,
      dialogListEdit: false,
      data: [],
      titileData: "",
      titileDataStatus: "",
      listData: [],
      form: {
        id: "",
        eva_titile_name: "",
        eva_titile_achieve_point: "",
        eva_id: this.$route.params.evaluationID
      },
      formList: {
        id: "",
        eva_list_name: "",
        eva_point: "",
        eva_titile_name_id: "",
        eva_id: this.$route.params.evaluationID
      },
      selectedData: { id: "", name: "" },
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
      let apiURL = "http://localhost:1337/evaluation/";
      let getID = this.$route.params.evaluationID;
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
    saveDataEditList: function() {
      let apiURL = "http://localhost:1337/evaluation/list/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.formList, setting)
        .then(response => {
          this.dialogListEdit = false;
          console.log("ok2");
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            this.formList.eva_list_name = "";
            this.formList.eva_point = "";
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
          this.$validator.reset();
          this.dialogListEdit = false;
        });
    },
    saveDataList: function() {
      let apiURL = "http://localhost:1337/evaluation/list/create";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.formList, setting)
        .then(response => {
          this.dialogList = false;
          console.log("ok2");
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            this.formList.eva_list_name = "";
            this.formList.eva_point = "";
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
          this.$validator.reset();
          this.dialogList = false;
        });
    },
    submitList(formList) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.saveDataList();
          return;
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
    saveData: function() {
      let apiURL = "http://localhost:1337/evaluation/titile/create";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          this.dialog = false;

          console.log("ok2");
          Swal.fire({
            title: "เพิ่มเรียบร้อย",
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
          this.dialog = false;
          this.$validator.reset();
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
    getTitileData: function() {
      let apiURL = "http://localhost:1337/evaluation/titile/";
      let getID = this.$route.params.evaluationID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.titileData = response.data.data),
            (this.titileDataStatus = response.data.message)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getListData: function() {
      let apiURL = "http://localhost:1337/evaluation/list/";
      let getID = this.$route.params.evaluationID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.listData = response.data.data), (this.isLoading = false)
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
    dialogListDel: function() {
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
          this.deleteDataList();
          console.log("ok1");
        }
      });
    },
    deleteDataList: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      let apiURL = "http://localhost:1337/evaluation/list/delete/";
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
            this.getTitileData();
            this.getListData();
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
    deleteDataAll: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      let apiURL = "http://localhost:1337/evaluation/titile/delete/";
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
            this.getTitileData();
            this.getListData();
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
    deleteData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      let apiURL = "http://localhost:1337/evaluation/titilelist/delete/";
      let getID = this.selectedData;
      this.axios
        .post(apiURL, getID, setting)
        .then(response => {
          this.deleteDataAll();
          console.log("ok2");
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
      this.$router.push("/evaluation");
    }
  },
  computed: {
    byTitile() {
      return this.listData.reduce((acc, data) => {
        (acc[data.eva_titile_name_id.eva_titile_name] =
          acc[data.eva_titile_name_id.eva_titile_name] || []).push({
          id: data.id,
          eva_list: data.eva_list_name,
          eva_point: data.eva_point
        });

        return acc;
      }, {});
    }
  }
};
</script>