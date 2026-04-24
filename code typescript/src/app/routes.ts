import { createBrowserRouter } from "react-router";
import { Layout } from "./components/Layout";
import { Dashboard } from "./components/Dashboard";
import { AddClassroom } from "./components/AddClassroom";
import { AddGroup } from "./components/AddGroup";
import { AddCourse } from "./components/AddCourse";
import { ViewData } from "./components/ViewData";
import { Schedule } from "./components/Schedule";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: Layout,
    children: [
      { index: true, Component: Dashboard },
      { path: "add-classroom", Component: AddClassroom },
      { path: "add-group", Component: AddGroup },
      { path: "add-course", Component: AddCourse },
      { path: "view-data", Component: ViewData },
      { path: "schedule", Component: Schedule },
    ],
  },
]);
