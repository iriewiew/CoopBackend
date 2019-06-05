import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    },
    {
      path: "/calendars",
      name: "calendars",
      component: () => import("./views/event/calendars.vue")
    },
    {
      path: "/setting",
      name: "setting",
      component: () => import("./views/setting.vue")
    },
    {
      path: '/github/:eventID',
      name: "github",
      beforeEnter() {location.href = 'http://github.com/:eventID'}
 },
    {
      path: "/notification",
      name: "notification",
      component: () => import("./views/notification.vue")
    },
    {
      path: "/notification/send",
      name: "notificationSend",
      component: () => import("./views/notification/notify_send.vue")
    },
    {
      path: "/event",
      name: "event",
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () =>
        import(/* webpackChunkName: "about" */ "./views/event.vue")
    },
    {
      path: "/student",
      name: "student",
      component: () => import("./views/student.vue")
    },
    {
      path: "/student/add",
      name: "studentAdd",
      component: () => import("./views/student/add.vue")
    },
    {
      path: "/student/:studentID/detail",
      name: "studentDetail",
      component: () => import("./views/student/detail.vue")
    },
    {
      path: "/teacher",
      name: "teacher",
      component: () => import("./views/teacher.vue")
    },
    {
      path: "/teacher/add",
      name: "teacherAdd",
      component: () => import("./views/teacher/add.vue")
    },
    {
      path: "/teacher/:teacherID/detail",
      name: "teacherDetail",
      component: () => import("./views/teacher/detail.vue")
    },
    {
      path: "/faculty",
      name: "faculty",
      component: () => import("./views/faculty.vue")
    },
    {
      path: "/faculty/add",
      name: "facultyAdd",
      component: () => import("./views/faculty/add.vue")
    },
    {
      path: "/faculty/major/:facultyID",
      name: "facultyMajor",
      component: () => import("./views/major/majorView.vue")
    },
    {
      path: "/faculty/:facultyID/detail",
      name: "facultyDetail",
      component: () => import("./views/faculty/detail.vue")
    },
    {
      path: "/faculty/major/:facultyID/add",
      name: "facultyMajorAdd",
      component: () => import("./views/major/add.vue")
    },
    {
      path: "/faculty/major/:facultyID/detail/:majorID",
      name: "facultyMajorDetail",
      component: () => import("./views/major/detail.vue")
    },
    {
      path: "/event/add",
      name: "eventAdd",
      component: () => import("./views/event/add.vue")
    },
    {
      path: "/event/:eventID/detail",
      name: "eventDetail",
      component: () => import("./views/event/detail.vue")
    },
    {
      path: "/evaluation",
      name: "evaluation",
      component: () => import("./views/evaluation.vue")
    },
    {
      path: "/event_report",
      name: "eventReport",
      component: () => import("./views/event/event_report.vue")
    },
    {
      path: "/event_report/:eventID/reg_list",
      name: "EventRegList",
      component: () => import("./views/event/event_reg_list.vue")
    },
    {
      path: "/evaluation/add",
      name: "evaluationAdd",
      component: () => import("./views/evaluation/add.vue")
    },
    {
      path: "/evaluation/:evaluationID/detail",
      name: "evaluationDetail",
      component: () => import("./views/evaluation/detail.vue")
    },
    {
      path: "/evaluation/:evaluationID/list",
      name: "evaluationList",
      component: () => import("./views/evaluation/eva_list.vue")
    },
    {
      path: "/evaluation/:evaluationID/list/add",
      name: "evaluationListAdd",
      component: () => import("./views/evaluation/eva_list_add.vue")
    },
    {
      path: "/evaluation/:evaluationID/list/detail/:evaListID",
      name: "evaluationListDetail",
      component: () => import("./views/evaluation/eva_list_detail.vue")
    },
    {
      path: "/evaluation_report",
      name: "evaluationReport",
      component: () => import("./views/evaluation/evaluation_report.vue")
    },
    {
      path: "/evaluation_report/:eventID/:evaID/",
      name: "evaluationReportDetail",
      component: () => import("./views/evaluation/eva_report_detail.vue")
    }
  ]
});
