import {FunctionComponent} from "react";

import { Table, Tag, Space } from 'antd';

const columns = [
  {
    title: 'Name',
    dataIndex: 'name',
    key: 'name',
    render: (text : any) => <a>{text}</a>,
  },
  {
    title: 'Action',
    key: 'action',
    render: (text: any, record: any) => (
      <Space size="middle">
        <a> Raport </a>
        <a> Delete {record.name}</a>
      </Space>
    ),
  },
];

const data = [
  {
    key: '1',
    name: 'Collection 1',
  },
  {
    key: '2',
    name: 'Collection 2',
  },
  {
    key: '3',
    name: 'Collection 3',
  },
];

export const CollectionsList: FunctionComponent = () => {

  return <div>
    <Table columns={columns} dataSource={data} />
  </div>;
};
