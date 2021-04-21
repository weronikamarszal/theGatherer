import {FunctionComponent, useEffect, useState} from "react";
import {Button, Col, Divider, Row, Table} from "antd";

export const CollectionObject: FunctionComponent = () => {

  // const [obejct, setObejct] = useState<any[]>([]);
  // useEffect(() => {
  //   const apiUrl = `http://127.0.0.1:8000/api/get-collections`;
  //   fetch(apiUrl)
  //     .then(res => res.json())
  //     .then(res => {
  //       setObejct(res)
  //     })
  // },);


  const columns = [
    {
      title: 'atributes',
      dataIndex: 'atributes',
    },
    {
      title: 'value',
      dataIndex: 'value',
    },
  ];

  const data = [
    {
      atributes: 'Name of item',
      value: 'Name',
    },
    {
      atributes: 'Year of publishing',
      value: '1976 year',
    },
  ];

  return <div>
    <Row>
      <Col span={12}>
        <h1>Item name</h1>
      </Col>
      <Col span={12}>
        <Button type="primary" shape="round">Delete</Button>
      </Col>
    </Row>
    <Divider />

    <img src={'https://lh3.googleusercontent.com/proxy/BzvqfUbO3-_gH7GaRQYWqRKwdt72koF2QeSzEOrPtxzaOIjPVtEBKVmDzpor2yAfKjozxHqMJlDIqiJEbjCC_ffNwlEPiAblcl8lEn0MomWB2GI4f5uFS9fK3aNNc_KI8RGLRq79oDg'} />
    <p></p>
    <Table columns={columns} dataSource={data} />

  </div>;
};
