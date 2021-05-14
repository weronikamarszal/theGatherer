import React, {FunctionComponent, useEffect, useState} from 'react';
import '../../index.css';
import {Link, useHistory, useParams} from "react-router-dom";
import {Button, Col, DatePicker, Form, Input, InputNumber, message, Modal, Popconfirm, Row, Select, Space} from "antd";
import './Collection.css'
import {AttributeFormItem} from "../../views/ObjectForm";
import {AttributeType} from "../../types/attributeType";

const {Option} = Select;

function handleChange(value) {
  console.log(`selected ${value}`);
}

const layout = {
  labelCol: {span: 24},
  wrapperCol: {span: 24},
};

export const FilterFormItem: FunctionComponent<{ attribute: any }> = ({attribute}) => {
  switch (attribute.type) {
    case AttributeType.STRING :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <Input/>
      </Form.Item>
    case AttributeType.FLOAT :
      return (
        <Form.Item label={attribute.label}
                   name={attribute.label}>
          <Space>
            From:
            <InputNumber/>
            To:
            <InputNumber/>
          </Space>
        </Form.Item>)
    case AttributeType.INT :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <Space>
        From:
        <InputNumber/>
        To:
        <InputNumber/>
        </Space>
      </Form.Item>
    case AttributeType.DATE :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <Space>
        From:
        <DatePicker/>
        To:
        <DatePicker/>
        </Space>
      </Form.Item>
    default:
      return <>NULL</>
  }
}


function getAttributes(collectionId: number) {
  const apiUrl = `/api/get-collection-attributes/${collectionId}`;
  return fetch(apiUrl)
    .then(res => res.json())
}

function confirm(collectionId: number, history) {
  fetch(`/api/delete-collection/${collectionId}`, {
    method: 'POST',
  })
    .then(res => res.json())
    .then(() => {
      history.push(`/all-collections`)
      message.success('Collection deleted')
    })
}

export const Collection: FunctionComponent = () => {
  const [isModalVisible, setIsModalVisible] = useState(false);

  const showModal = () => {
    setIsModalVisible(true);
  };

  const handleOk = () => {
    setIsModalVisible(false);
  };

  const handleCancel = () => {
    setIsModalVisible(false);
  };

  let params = useParams<any>();
  const collectionId = Number(params.id)
  const history = useHistory();

  const [collection, setCollection] = useState<any>({});
  useEffect(() => {
    fetch(`/api/get-collections/${collectionId}`)
      .then(res => res.json())
      .then(res => {
        setCollection(res)
      })
  }, [setCollection]);


  const [collectionItems, setCollectionsItems] = useState<any[]>([]);
  useEffect(() => {
    const apiUrl = `/api/get-collection-objects/${collectionId}`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setCollectionsItems(res)
      })
  }, [setCollectionsItems]);

  const [collectionAttributes, setCollectionAttributes] = useState<any[]>([]);
  useEffect(() => {
    getAttributes(collectionId)
      .then(res => {
        setCollectionAttributes(res)
      })
  }, [setCollectionAttributes]);

  return <>
    <div>
      <div className='headWrapper'>
        <div className='thisCollectionTitle'>
          <h1>{collection.name}</h1>
        </div>
        <div className='addButtonWrapper'>
          <Space>
            <Button shape="round"><Link to={`/add-object/${params.id}`}> Add Item </Link></Button>
            <Button shape="round"><Link to={`/edit-collection/${params.id}`}> Edit collection </Link>
            </Button>
            <Popconfirm
              title="Are you sure delete this object?"
              onConfirm={() => confirm(collectionId, history)}
              okText="Yes"
              cancelText="No">
              <Button shape="round">Delete</Button>
            </Popconfirm>
          </Space>
        </div>
      </div>
      <hr className='horizontalLine'/>
      <Button type="primary" onClick={showModal}>
        Filter by
      </Button>
      <Modal title="Filter by" visible={isModalVisible} onOk={handleOk} onCancel={handleCancel}>
        <Row>
          <Form
            name="basic"
            {...layout}
          >
            <Col>
              {collectionAttributes.map((attribute) =>
                <FilterFormItem attribute={attribute}/>
              )}
            </Col>

            <Col>
              <Form.Item>
                <Space>
                  Sort by:
                  <Select style={{width: 120}} onChange={handleChange}>
                    {collectionAttributes.map((attribute) =>
                      <Option value={attribute.label}>{attribute.label}</Option>
                    )}
                  </Select>
                  Direction
                  <Select style={{width: 120}} onChange={handleChange}>
                    <Option value="asc">Asc</Option>
                    <Option value="desc">Desc</Option>
                  </Select>
                </Space>
              </Form.Item>
            </Col>
          </Form>
        </Row>
      </Modal>

      <Row>
        {collectionItems.map((item) =>
          <Col span={8} className='collection-item-wrapper'>
            <div className='collection-item'>
              <img className='itemImage' src={item.photo_path} alt="itemImage"/>
              <div className='itemDescription'>
                {item.name}
              </div>
              <Button type="primary"><Link to={`/object/${item.id}`}> View </Link></Button>
            </div>
          </Col>
        )}
      </Row>

    </div>
  </>
};



