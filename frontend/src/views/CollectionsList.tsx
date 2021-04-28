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
          <a> Raport </a>
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
