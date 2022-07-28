import { queryStringFromParams } from "@/http";
const baseUrl = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/';

const baseParams = {
    retmode: 'json',
    db: 'pubmed'
}

const mergeParams = (...args) => {
    let merged = {}
    for (let i = 0; i < args.length; i++) {
        if (typeof args[i] !== 'object') {
            throw Error('expect all arguments to be objects');
        }
        merged = {...merged, ...args[i]};
    }
    return merged;
}

const esearch = (term, options = {}, ) => {
    const defaultParams = {retmax: 20, retstart: 0}

    const url = `${baseUrl}/esearch.fcgi`;
    const params = mergeParams(baseParams, defaultParams, options, {term})

    const searchUrl = url+queryStringFromParams(params);

    return fetch(searchUrl)
        .then(rsp => {
            console.log('search complete.')
            return rsp.json()
        })
}

const esummary = (ids, options = {}) => {
    const url = `${baseUrl}/esummary.fcgi`;
    const params = mergeParams(baseParams, options, {id: ids.join(',')})

    const summaryUrl = url+queryStringFromParams(params);

    return fetch(summaryUrl)
        .then(rsp => {
            return rsp.json()
        })
}



export {
    esearch,
    esummary
}
