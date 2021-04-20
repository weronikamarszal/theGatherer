import {FunctionComponent} from "react";
import {CollectionsList} from "../../views/CollectionsList";

export const MyCollections: FunctionComponent = () => {

  return <div>
    <h1>THE GATHERER</h1>
    <h3>Your collections:</h3>
    <CollectionsList/>
  </div>;
};
