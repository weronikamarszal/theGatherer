import {FunctionComponent, useEffect, useState} from "react";
import {Table, Tag, Space} from 'antd';

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
          <a> Show </a>
          <a> Raport </a>
          {props.onDelete && <a> Delete </a>}
        </Space>
      ),
    },
  ];

  return <div>
    <Table columns={columns} dataSource={props.collectionsList}/>
  </div>;
};
