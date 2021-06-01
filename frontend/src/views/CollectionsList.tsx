import React, {FunctionComponent, useEffect, useState} from "react";
import {Table, Tag, Space, Button} from 'antd';
import {Link} from "react-router-dom";
import {Buffer} from "buffer";

export const CollectionsList: FunctionComponent<{ onDelete?: () => void, collectionsList: any[]}> = (props) => {
  const columns = [
    {
      title: 'Name',
      dataIndex: 'name',
      key: 'name',
      render: (text: any) => <a>{text}</a>,
    },
    {
      title: 'Action',
      key: 'action',
      render: (text: any, record: any) => (
        <Space size="large">
          <Link to={`/collection/${record.id}`}> Show </Link>
          <a onClick={() => {
            fetch(`http://127.0.0.1:8000/api/get-collection-csv/${record.id}`, {
              method: 'GET',
            })
              .then(response => response.blob())
              .then(blob => {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = "filename.csv";
                document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
                a.click();
                a.remove();  //afterwards we remove the element again
              });
          }}>Download raport</a>
          {props.onDelete && <a> Delete </a>}
        </Space>
      ),
    },
  ];

  return <div>
    <Table columns={columns} dataSource={props.collectionsList}/>
    <Button type="primary"><Link to={`/add-collection`}> Add collection </Link></Button>
  </div>;
};
