import {FunctionComponent} from "react";
import { Table, Tag, Space } from 'antd';

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

export const CollectionsList: FunctionComponent<{onDelete?: () => void}> = (props) => {
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
        <Space size="large">
          <a> Show </a>
          <a> Raport </a>
          {props.onDelete && <a> Delete </a>}
        </Space>
      ),
    },
  ];

  return <div>
    <Table columns={columns} dataSource={data} />
  </div>;
};
