import React, { Component } from "react";
import DataTable from "./components/DataTable";

export default class ReactDataTableApp extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const columns = ['title', 'address', 'city', 'link', 'image'];
    return (
      <DataTable fetchUrl="/api/source-data-transformed" columns={columns} />
    );
  }
}