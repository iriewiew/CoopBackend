<template>
  <div class="menuLeft">
    <br>
    <v-list dense>
      <v-list-tile to="/">
        <v-list-tile-action>
          <v-icon>dashboard</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Dashboard</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <!-- <v-list-tile to="/calendars">
        <v-list-tile-action>
          <v-icon>dashboard</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>ปฏิทินกิจกรรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>-->
      <span style=" color: #afafaf" class="text-sm">--- การจัดการข้อมูล ---</span>

      <v-list-group prepend-icon="face" value="true">
        <template v-slot:activator>
          <v-list-tile>
            <v-list-tile-title>จัดการข้อมูล</v-list-tile-title>
          </v-list-tile>
        </template>

        <v-list-tile to="/student">
          <v-list-tile-action></v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>- จัดการข้อมูลนักศึกษา</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/teacher">
          <v-list-tile-action></v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>- จัดการข้อมูลคณาจารย์</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/faculty">
          <v-list-tile-action></v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>- จัดการข้อมูลคณะ</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <!-- 
                        <v-list-tile to="/major">
          <v-list-tile-action></v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>-  จัดการข้อมูลสาขา</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>-->
      </v-list-group>

      <v-list-tile to="/event">
        <v-list-tile-action>
          <v-icon>event</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>จัดการกิจกรรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile to="/evaluation">
        <v-list-tile-action>
          <v-icon>assignment</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>จัดการแบบประเมิน</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <span style=" color: #afafaf" class="text-sm">--- รายงานผลต่างๆ ---</span>

      <v-list-tile to="/event_report">
        <v-list-tile-action>
          <v-icon>local_activity</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>รายงานผลการเข้าร่วมกิจกรรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile to="/evaluation_report">
        <v-list-tile-action>
          <v-icon>equalizer</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>รายงานผลการประเมินกิจกรรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
            <v-list-tile to="/static">
        <v-list-tile-action>
          <v-icon>donut_large</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>รายงานสถิติการจัดกิจจกรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <span style=" color: #afafaf" class="text-sm">--- อื่นๆ ---</span>
      <v-list-tile to="/notification">
        <v-list-tile-action>
          <v-icon>insert_comment</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>การแจ้งเตือนกิจกรรม</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile to="/usermanage">
        <v-list-tile-action>
          <v-icon>group</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>จัดการผู้ใช้งานระบบ</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile to="/setting">
        <v-list-tile-action>
          <v-icon>settings</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>การตั้งค่า</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-layout align-end justify-center row fill-height>
        <v-fab-transition>
          <v-btn flat block fixed bottom color="red" @click="alLogout">
            <v-icon>power_settings_new</v-icon> ออกจากระบบ
          </v-btn>
        </v-fab-transition>
      </v-layout>
    </v-list>
  </div>
</template>
<script>
export default {
  data() {
    return {
      admins: [["Management", "people_outline"], ["Settings", "settings"]]
    };
  },
  methods: {
    logout: function() {
      localStorage.clear();
      this.$store.state.getApiData.tokenkey = null;
      this.$store.state.getApiData.id = null;
      this.$store.state.getApiData.name = null;
      this.$store.state.getApiData.status = null;
      this.$store.state.getApiData.username = null;
    },
    alLogout: function() {
      Swal.fire({
        title: "ต้องการออกจากระบบหรือไม่",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F56C6C",
        cancelButtonText: "ยกเลิก",
        confirmButtonText: "ยืนยัน"
      }).then(result => {
        if (result.value) {
          Swal.fire({
            type: "success",
            title: "ออกจากระบบเรียบร้อย",
            showConfirmButton: false,
            timer: 1500
          });
          console.log("ok1");
          this.logout();
        }
      });
    }
  }
};
</script>

<style scoped>
.text-sm {
  font-size: 9px;
}
.menuLeft {
  padding-left: 20px;
  padding-right: 20px;
}

.tile {
  margin: 5px;
  border-radius: 20px;
}
.tile:hover {
  background: dimgrey;
  border-radius: 20px;
}
.tile:active {
  background: dimgrey;
  border-radius: 20px;
}
.tile:visited {
  background: dimgrey;
  border-radius: 20px;
}
</style>