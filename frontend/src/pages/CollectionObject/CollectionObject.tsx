import React, {FunctionComponent, useEffect, useState} from "react";
import {Button, Col, Divider, message, Popconfirm, Row, Space, Table} from "antd";
import {Link, useHistory, useParams, useRouteMatch,} from 'react-router-dom';
import {ColumnsType} from "antd/es/table";
import './CollectionObject.css'
import {EditOutlined} from "@ant-design/icons";

function confirm(objectId: number, collectionId: number, history) {
  fetch(`/api/delete-object/${objectId}`, {
    method: 'POST',
  })
    .then(res => res.json())
    .then(() => {
      history.push(`/collection/${collectionId}`)
      message.success('Object deleted')
    })
}

export const CollectionObject: FunctionComponent = () => {
  let objectId = useParams<any>();
  const history = useHistory();

  const [collectionObject, setObject] = useState<any>({});
  const [collectionAttributes, setAttributes] = useState<any>([]);
  useEffect(() => {
    const apiUrl = `/api/get-object/${objectId.id}`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setObject(res)
        return fetch(`/api/get-collection-attributes/${res.collection_id}`)
      })
      .then(res => res.json())
      .then(res => setAttributes(res))
  }, [setObject, setAttributes]);

  const columns: ColumnsType = [
    {
      title: 'attribute',
      dataIndex: 'attribute',
      render: value => <div>{value.label}</div>
    },
    {
      title: 'value',
      dataIndex: 'value',
      render: value => <div>{value}</div>
    },
  ];

  const data = [];
  collectionAttributes.forEach(attribute => data.push({
    attribute: attribute,
    value: collectionObject[attribute.label]
  }))

  const photoPath = collectionObject.photo_path ? <img src={collectionObject.photo_path}/> : '';

  return <div>
    <Row>
      <Col span={12}>
        <h1>
          {collectionObject.name}
        </h1>
      </Col>
      <Col span={12}>
        <Space>
          <Button type="primary" shape="round"><Link to={`/edit-object/${collectionObject.id}`}>Edit</Link></Button>
          <Button type="primary" shape="round" onClick={() => {

            fetch(`http://127.0.0.1:8000/api/get-pdf/${collectionObject.id}`, {
              method: 'GET',
            })
              .then(response => response.blob())
              .then(blob => {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.target="_blank"
                document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
                a.click();
                a.remove();  //afterwards we remove the element again
              });
          }}>Show raport</Button>

          <Button type="primary" shape="round" onClick={() => {
            fetch(`http://127.0.0.1:8000/api/get-pdf/${collectionObject.id}`, {
              method: 'GET',
            })
              .then(response => response.blob())
              .then(blob => {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = "filename.txt";
                document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
                a.click();
                a.remove();  //afterwards we remove the element again
              });
          }}>Download raport</Button>


          <Popconfirm
            title="Are you sure delete this object?"
            onConfirm={() => confirm(objectId.id, collectionObject.collection_id, history)}
            okText="Yes"
            cancelText="No">
            <Button shape="round">Delete</Button>
          </Popconfirm>
        </Space>
      </Col>
    </Row>
    <Divider/>
    <div className="item-image">
      {photoPath}
    </div>

    <p></p>
    <Table columns={columns} dataSource={data}/>
  </div>;

};
