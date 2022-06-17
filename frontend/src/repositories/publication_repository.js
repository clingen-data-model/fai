import {Publication} from "../domain/publication_entity";
import BaseRepository from "./base_repository";

export default (new BaseRepository('/publications', {entityClass: Publication}));