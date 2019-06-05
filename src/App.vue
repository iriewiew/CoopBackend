<template>
  <v-app>
    <transition name="fade" mode="out-in">
      <div v-if="$store.state.getApiData.tokenkey">
        <v-navigation-drawer clipped fixed v-model="drawer" app>
          <MenuLeft/>
        </v-navigation-drawer>

        <v-toolbar fixed clipped-left app dark style="background-color: coral;">
          <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
          <v-toolbar-title>
            <span>
              <b>ระบบจัดการกิจกรรม</b>
            </span>
            <span>ศูนย์สหกิจศึกษา</span>
          </v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-content>
          <v-container>
            <transition name="fade" mode="out-in">
              <router-view/>
            </transition>
          </v-container>
        </v-content>
      </div>
      <div v-else id="inspire">
        <br>
        <br>
        <v-flex align-center justify-center layout text-xs-center>
          <v-avatar size="250">
            <img src="../src/assets/128x128.png">
          </v-avatar>
        </v-flex>
        <br>
        <v-content>
          <v-layout justify-center>
            <span class="align-center justify-center">
              <span class="display-1">
                <b>ศูนย์สหกิจศึกษา มหาวิทยาลัยราชภัฏลำปาง</b>
                <br>
              </span>
              <center class="headline">COOPERATIVE EDUCATION CENTER</center>
            </span>
          </v-layout>
          <v-container fluid fill-height>
            <v-layout align-start justify-center>
              <v-flex xs12 sm8 md4>
                <!-- <v-toolbar dark color="amber darken-4">
                    <v-toolbar-title>Login form</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>-->
                <v-card-text>
                  <v-form>
                    <v-text-field
                      color="amber darken-4"
                      prepend-icon="person"
                      name="login"
                      label="Username"
                      type="text"
                      v-model="form.username"
                      v-validate="'required|'"
                      :error-messages="errors.collect('username')"
                      data-vv-name="username"
                      required
                    ></v-text-field>
                    <v-text-field
                      color="amber darken-4"
                      prepend-icon="lock"
                      name="password"
                      label="Password"
                      id="password"
                      type="password"
                      v-model="form.password"
                      v-validate="'required|'"
                      :error-messages="errors.collect('password')"
                      data-vv-name="password"
                      required
                    ></v-text-field>
                  </v-form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                </v-card-actions>

                <br>
                <v-layout align-center justify-center row fill-height>
                  <v-btn block color="amber darken-4" dark @click="submitForm('form')">เข้าสู่ระบบ</v-btn>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-content>
      </div>
    </transition>
  </v-app>
</template>

<script>
import MenuLeft from "./components/menu";

export default {
  name: "App",
  components: {
    MenuLeft
  },
  mounted() {
    this.$validator.localize("th", this.dictionary);
    if (localStorage.tokenkey)
      this.$store.state.getApiData.tokenkey = localStorage.tokenkey;
    this.getusername();
    this.getname();
    this.getid();
    this.getstatus();
  },
  data() {
    return {
      drawer: true,
      form: {
        username: "",
        password: ""
      },
      dictionary: {
        custom: {
          username: {
            required: () => "โปรดกรอกชื่อบัญชีผู้ใช้ ",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          password: {
            required: () => "โปรดกรอกรหัสผ่าน",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          }
        }
      }
      //
    };
  },
  methods: {
    getTokenKey: function() {
      if (localStorage.tokenkey)
        this.$store.state.getApiData.tokenkey = localStorage.tokenkey;
    },
    getusername: function() {
      if (localStorage.username)
        this.$store.state.getApiData.username = localStorage.username;
    },
    getname: function() {
      if (localStorage.name)
        this.$store.state.getApiData.name = localStorage.name;
    },
    getid: function() {
      if (localStorage.id) this.$store.state.getApiData.id = localStorage.id;
    },
    getstatus: function() {
      if (localStorage.status)
        this.$store.state.getApiData.status = localStorage.status;
    },

    login: function() {
      let apiURL = "http://localhost:1337/api/users/login";
      this.axios
        .post(apiURL, this.form)
        .then(response => {
          console.log(response);
          localStorage.tokenkey = "Bearer " + response.data.token;
          localStorage.id = response.data.id;
          localStorage.username = response.data.username;
          localStorage.name = response.data.name;
          localStorage.status = response.data.status;

          {
            Swal.fire({
              title: "เข้าสู่ระบบเรียบร้อย",
              type: "success",
              showConfirmButton: false,
              timer: 1500
            }).then(response => {
              console.log("ok3");
              this.getTokenKey();
              this.getusername();
              this.getname();
              this.getid();
              this.getstatus();
            });
          }
        })
        .catch(err => {
          console.log(err.response.data);
          console.log(err.response.status);
          console.log(err.response.headers);
          if ((err.response.status == 404) | (err.response.status == 400)) {
            Swal.fire({
              type: "error",
              title: "ชื่อผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง",
              showConfirmButton: false,
              timer: 1500
            });
          }
        });
    },
    submitForm(form) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.login();
          return;
        }
      });
    }
  }
};
</script>
<style>
/* @import url("https://fonts.googleapis.com/css?family=Kanit"); */
.bgColor {
  background-color: orange;
}
.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.3s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
/* body,
#app {
  font-family: "Kanit", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
} */
</style>

