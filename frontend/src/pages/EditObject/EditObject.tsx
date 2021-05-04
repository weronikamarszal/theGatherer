import {FunctionComponent, useEffect, useState} from "react";
import {ObjectForm} from "../../views/ObjectForm";
import {useHistory, useParams} from "react-router-dom";
import {AttributeType} from "../../types/attributeType";
import moment from "moment";
import './EditObject.css'

const onFinish = (values: any) => {
  console.log('Success:', values);
}

function mapObjectToFormValue(collectionObject: any, attributes: any[]) {
  attributes
    .filter(i => i.type === AttributeType.DATE)
    .forEach(i => {
      collectionObject[i.label] = moment(collectionObject[i.label])
    })
}

function getObjectWithAttributes(objectId: number): Promise<{object: any, attributes: any[]}> {
  let object;
  let attributes;

  return fetch(`/api/get-object/${objectId}`)
    .then(res => res.json())
    .then(res => {
      object = res;
      return fetch(`/api/get-collection-attributes/${res.collection_id}`)
    })
    .then(res => res.json())
    .then(res => {
      attributes = res;
      return {object: object, attributes: attributes}
    });
}

export const EditObject: FunctionComponent = () => {
  let params = useParams<any>();
  const history = useHistory();
  const objectId = Number(params.id)

  const [collectionObject, setObject] = useState<any>(null);
  const [collectionAttributes, setAttributes] = useState<any>([]);
  useEffect(() => {
    getObjectWithAttributes(objectId)
      .then(res => {
        mapObjectToFormValue(res.object, res.attributes);
        setObject(res.object);
        setAttributes(res.attributes);
      })
  }, [setObject, setAttributes]);

  return <div>
    <h1>Edit your item</h1>
    {collectionObject &&
    <ObjectForm onFinish={onFinish} attributes={collectionAttributes} initValue={collectionObject}
                aboveUpload={<img src={collectionObject.photo_path} className='edit-image'/>}/>}
  </div>;
}
