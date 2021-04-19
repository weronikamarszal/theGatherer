import {FunctionComponent} from "react";
import {CollectionsList} from "../../views/CollectionsList";

export const AllCollections: FunctionComponent = () => {

  return <div>
    <h1>THE GATHERER</h1>
    <h3>All collections:</h3>
    <CollectionsList/>
  </div>;
};
