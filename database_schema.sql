--
-- PostgreSQL database dump
--

\restrict jH00HW985Tf1zRL9HaWVJMzj5ICSTe0kNQa0W9LLvkshMYgemxFHYygXlBAhXtp

-- Dumped from database version 16.15 (Ubuntu 16.15-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.15 (Ubuntu 16.15-0ubuntu0.24.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: addtime(timestamp without time zone, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.addtime(ts timestamp without time zone, t text) RETURNS timestamp without time zone
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT ts + t::interval;
$$;


ALTER FUNCTION public.addtime(ts timestamp without time zone, t text) OWNER TO postgres;

--
-- Name: convert_tz(timestamp without time zone, text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.convert_tz(dt timestamp without time zone, from_tz text, to_tz text) RETURNS timestamp without time zone
    LANGUAGE plpgsql IMMUTABLE
    AS $$
BEGIN
    RETURN (dt AT TIME ZONE 'UTC' AT TIME ZONE to_tz)::timestamp;
EXCEPTION WHEN OTHERS THEN
    RETURN dt;
END;
$$;


ALTER FUNCTION public.convert_tz(dt timestamp without time zone, from_tz text, to_tz text) OWNER TO postgres;

--
-- Name: date(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.date(ts timestamp without time zone) RETURNS date
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT ts::date;
$$;


ALTER FUNCTION public.date(ts timestamp without time zone) OWNER TO postgres;

--
-- Name: date_add(date, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.date_add(d date, interval_expr text) RETURNS date
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT (d + interval_expr::interval)::date;
$$;


ALTER FUNCTION public.date_add(d date, interval_expr text) OWNER TO postgres;

--
-- Name: date_format(date, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.date_format(d date, fmt text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT to_char(d, replace(replace(replace(fmt, '%Y', 'YYYY'), '%m', 'MM'), '%d', 'DD'));
$$;


ALTER FUNCTION public.date_format(d date, fmt text) OWNER TO postgres;

--
-- Name: date_format(timestamp without time zone, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.date_format(d timestamp without time zone, fmt text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT to_char(d, replace(replace(replace(fmt, '%Y', 'YYYY'), '%m', 'MM'), '%d', 'DD'));
$$;


ALTER FUNCTION public.date_format(d timestamp without time zone, fmt text) OWNER TO postgres;

--
-- Name: datediff(date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.datediff(d1 date, d2 date) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT (d1 - d2)::integer;
$$;


ALTER FUNCTION public.datediff(d1 date, d2 date) OWNER TO postgres;

--
-- Name: datediff(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.datediff(d1 text, d2 text) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT (d1::date - d2::date)::integer;
$$;


ALTER FUNCTION public.datediff(d1 text, d2 text) OWNER TO postgres;

--
-- Name: datediff(timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.datediff(d1 timestamp without time zone, d2 timestamp without time zone) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT (d1::date - d2::date)::integer;
$$;


ALTER FUNCTION public.datediff(d1 timestamp without time zone, d2 timestamp without time zone) OWNER TO postgres;

--
-- Name: day(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.day(d date) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(DAY FROM d)::integer;
$$;


ALTER FUNCTION public.day(d date) OWNER TO postgres;

--
-- Name: day(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.day(d timestamp without time zone) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(DAY FROM d)::integer;
$$;


ALTER FUNCTION public.day(d timestamp without time zone) OWNER TO postgres;

--
-- Name: find_in_set(integer, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.find_in_set(needle integer, haystack text) RETURNS boolean
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT FIND_IN_SET(needle::text, haystack);
$$;


ALTER FUNCTION public.find_in_set(needle integer, haystack text) OWNER TO postgres;

--
-- Name: find_in_set(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.find_in_set(needle text, haystack text) RETURNS boolean
    LANGUAGE plpgsql IMMUTABLE
    AS $$
DECLARE
    arr text[];
    i integer;
BEGIN
    IF needle IS NULL OR haystack IS NULL THEN
        RETURN NULL;
    END IF;
    IF haystack = '' THEN
        RETURN FALSE;
    END IF;
    arr := string_to_array(haystack, ',');
    FOR i IN 1..array_length(arr, 1) LOOP
        IF arr[i] = needle THEN
            RETURN TRUE;
        END IF;
    END LOOP;
    RETURN FALSE;
END;
$$;


ALTER FUNCTION public.find_in_set(needle text, haystack text) OWNER TO postgres;

--
-- Name: group_concat_sep_sfunc(text, text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.group_concat_sep_sfunc(text, text, text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $_$
    SELECT CASE
        WHEN $1 IS NULL THEN $2
        WHEN $2 IS NULL THEN $1
        ELSE $1 || $3 || $2
    END;
$_$;


ALTER FUNCTION public.group_concat_sep_sfunc(text, text, text) OWNER TO postgres;

--
-- Name: group_concat_sfunc(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.group_concat_sfunc(text, text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $_$
    SELECT CASE
        WHEN $1 IS NULL THEN $2
        WHEN $2 IS NULL THEN $1
        ELSE $1 || ',' || $2
    END;
$_$;


ALTER FUNCTION public.group_concat_sfunc(text, text) OWNER TO postgres;

--
-- Name: ifnull(anyelement, anyelement); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.ifnull(anyelement, anyelement) RETURNS anyelement
    LANGUAGE sql IMMUTABLE
    AS $_$
    SELECT COALESCE($1, $2);
$_$;


ALTER FUNCTION public.ifnull(anyelement, anyelement) OWNER TO postgres;

--
-- Name: month(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.month(d date) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(MONTH FROM d)::integer;
$$;


ALTER FUNCTION public.month(d date) OWNER TO postgres;

--
-- Name: month(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.month(d timestamp without time zone) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(MONTH FROM d)::integer;
$$;


ALTER FUNCTION public.month(d timestamp without time zone) OWNER TO postgres;

--
-- Name: mysql_if(boolean, anyelement, anyelement); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.mysql_if(boolean, anyelement, anyelement) RETURNS anyelement
    LANGUAGE sql IMMUTABLE
    AS $_$
    SELECT CASE WHEN $1 THEN $2 ELSE $3 END;
$_$;


ALTER FUNCTION public.mysql_if(boolean, anyelement, anyelement) OWNER TO postgres;

--
-- Name: now(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.now() RETURNS timestamp without time zone
    LANGUAGE sql STABLE
    AS $$
    SELECT CURRENT_TIMESTAMP::timestamp;
$$;


ALTER FUNCTION public.now() OWNER TO postgres;

--
-- Name: round(double precision, integer); Type: FUNCTION; Schema: public; Owner: rise_user
--

CREATE FUNCTION public.round(val double precision, scale integer) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE
    AS $$
BEGIN
    IF val IS NULL THEN
        RETURN NULL;
    END IF;
    RETURN ROUND(val::numeric, scale);
END;
$$;


ALTER FUNCTION public.round(val double precision, scale integer) OWNER TO rise_user;

--
-- Name: str_to_date(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.str_to_date(str text, fmt text) RETURNS date
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT to_date(str, replace(replace(replace(fmt, '%Y', 'YYYY'), '%m', 'MM'), '%d', 'DD'));
$$;


ALTER FUNCTION public.str_to_date(str text, fmt text) OWNER TO postgres;

--
-- Name: time_to_sec(interval); Type: FUNCTION; Schema: public; Owner: rise_user
--

CREATE FUNCTION public.time_to_sec(val interval) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE
    AS $$
BEGIN
    IF val IS NULL THEN
        RETURN 0;
    END IF;
    RETURN EXTRACT(EPOCH FROM val);
END;
$$;


ALTER FUNCTION public.time_to_sec(val interval) OWNER TO rise_user;

--
-- Name: time_to_sec(numeric); Type: FUNCTION; Schema: public; Owner: rise_user
--

CREATE FUNCTION public.time_to_sec(val numeric) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE
    AS $$
BEGIN
    RETURN COALESCE(val, 0);
END;
$$;


ALTER FUNCTION public.time_to_sec(val numeric) OWNER TO rise_user;

--
-- Name: timediff(timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: rise_user
--

CREATE FUNCTION public.timediff(ts1 timestamp without time zone, ts2 timestamp without time zone) RETURNS interval
    LANGUAGE plpgsql IMMUTABLE
    AS $$
BEGIN
    IF ts1 IS NULL OR ts2 IS NULL THEN
        RETURN NULL;
    END IF;
    RETURN ts1 - ts2;
END;
$$;


ALTER FUNCTION public.timediff(ts1 timestamp without time zone, ts2 timestamp without time zone) OWNER TO rise_user;

--
-- Name: timestampdiff(text, timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.timestampdiff(unit text, ts1 timestamp without time zone, ts2 timestamp without time zone) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT CASE upper(unit)
        WHEN 'SECOND' THEN EXTRACT(EPOCH FROM (ts2 - ts1))::integer
        WHEN 'MINUTE' THEN (EXTRACT(EPOCH FROM (ts2 - ts1)) / 60)::integer
        WHEN 'HOUR'   THEN (EXTRACT(EPOCH FROM (ts2 - ts1)) / 3600)::integer
        WHEN 'DAY'    THEN (ts2::date - ts1::date)::integer
        WHEN 'MONTH'  THEN (EXTRACT(YEAR FROM age(ts2, ts1)) * 12 + EXTRACT(MONTH FROM age(ts2, ts1)))::integer
        WHEN 'YEAR'   THEN EXTRACT(YEAR FROM age(ts2, ts1))::integer
        ELSE 0
    END;
$$;


ALTER FUNCTION public.timestampdiff(unit text, ts1 timestamp without time zone, ts2 timestamp without time zone) OWNER TO postgres;

--
-- Name: trunc(double precision, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trunc(val double precision, scale integer) RETURNS numeric
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT TRUNC(val::numeric, scale);
$$;


ALTER FUNCTION public.trunc(val double precision, scale integer) OWNER TO postgres;

--
-- Name: unix_timestamp(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.unix_timestamp() RETURNS bigint
    LANGUAGE sql STABLE
    AS $$
    SELECT EXTRACT(EPOCH FROM NOW())::bigint;
$$;


ALTER FUNCTION public.unix_timestamp() OWNER TO postgres;

--
-- Name: unix_timestamp(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.unix_timestamp(ts timestamp without time zone) RETURNS bigint
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(EPOCH FROM ts)::bigint;
$$;


ALTER FUNCTION public.unix_timestamp(ts timestamp without time zone) OWNER TO postgres;

--
-- Name: year(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.year(d date) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(YEAR FROM d)::integer;
$$;


ALTER FUNCTION public.year(d date) OWNER TO postgres;

--
-- Name: year(timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.year(d timestamp without time zone) RETURNS integer
    LANGUAGE sql IMMUTABLE
    AS $$
    SELECT EXTRACT(YEAR FROM d)::integer;
$$;


ALTER FUNCTION public.year(d timestamp without time zone) OWNER TO postgres;

--
-- Name: group_concat(text); Type: AGGREGATE; Schema: public; Owner: postgres
--

CREATE AGGREGATE public.group_concat(text) (
    SFUNC = public.group_concat_sfunc,
    STYPE = text
);


ALTER AGGREGATE public.group_concat(text) OWNER TO postgres;

--
-- Name: group_concat(text, text); Type: AGGREGATE; Schema: public; Owner: postgres
--

CREATE AGGREGATE public.group_concat(text, text) (
    SFUNC = public.group_concat_sep_sfunc,
    STYPE = text
);


ALTER AGGREGATE public.group_concat(text, text) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ncs_accounting_grants; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_accounting_grants (
    id integer NOT NULL,
    federation_code character varying(32) NOT NULL,
    federation_name character varying(255) NOT NULL,
    grant_quarter character varying(32) NOT NULL,
    allocated_amount numeric(15,2) DEFAULT 0.00,
    disbursed_amount numeric(15,2) DEFAULT 0.00,
    accountability_status character varying(50) DEFAULT 'VERIFIED'::character varying,
    disbursement_date date DEFAULT CURRENT_DATE,
    notes text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_accounting_grants OWNER TO rise_user;

--
-- Name: ncs_accounting_grants_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_accounting_grants_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_accounting_grants_id_seq OWNER TO rise_user;

--
-- Name: ncs_accounting_grants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_accounting_grants_id_seq OWNED BY public.ncs_accounting_grants.id;


--
-- Name: ncs_accounting_ledgers; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_accounting_ledgers (
    id integer NOT NULL,
    voucher_number character varying(64) NOT NULL,
    posting_date date DEFAULT CURRENT_DATE,
    vote_head_code character varying(32) NOT NULL,
    vote_head_name character varying(128) NOT NULL,
    description text NOT NULL,
    debit_amount numeric(15,2) DEFAULT 0.00,
    credit_amount numeric(15,2) DEFAULT 0.00,
    status character varying(32) DEFAULT 'POSTED'::character varying,
    poster_name character varying(100) DEFAULT 'Chief Accountant'::character varying,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_accounting_ledgers OWNER TO rise_user;

--
-- Name: ncs_accounting_ledgers_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_accounting_ledgers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_accounting_ledgers_id_seq OWNER TO rise_user;

--
-- Name: ncs_accounting_ledgers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_accounting_ledgers_id_seq OWNED BY public.ncs_accounting_ledgers.id;


--
-- Name: ncs_accounting_reconciliations; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_accounting_reconciliations (
    id integer NOT NULL,
    reconciliation_ref character varying(64) NOT NULL,
    bank_account_name character varying(128) NOT NULL,
    bank_account_number character varying(64) NOT NULL,
    statement_date date DEFAULT CURRENT_DATE,
    system_balance numeric(15,2) DEFAULT 0.00,
    bank_statement_balance numeric(15,2) DEFAULT 0.00,
    variance numeric(15,2) DEFAULT 0.00,
    status character varying(32) DEFAULT 'RECONCILED'::character varying,
    reconciled_by character varying(100) DEFAULT 'Senior Accountant'::character varying,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_accounting_reconciliations OWNER TO rise_user;

--
-- Name: ncs_accounting_reconciliations_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_accounting_reconciliations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_accounting_reconciliations_id_seq OWNER TO rise_user;

--
-- Name: ncs_accounting_reconciliations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_accounting_reconciliations_id_seq OWNED BY public.ncs_accounting_reconciliations.id;


--
-- Name: ncs_accounting_vote_clearance; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_accounting_vote_clearance (
    id integer NOT NULL,
    requisition_ref character varying(64) NOT NULL,
    requesting_department character varying(100) NOT NULL,
    vote_head_code character varying(32) NOT NULL,
    vote_head_title character varying(128) NOT NULL,
    requested_amount numeric(15,2) DEFAULT 0.00,
    available_budget numeric(15,2) DEFAULT 0.00,
    clearance_status character varying(32) DEFAULT 'PASSED'::character varying,
    clearance_officer character varying(100) DEFAULT 'Senior Accountant'::character varying,
    clearance_date date DEFAULT CURRENT_DATE,
    remarks text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_accounting_vote_clearance OWNER TO rise_user;

--
-- Name: ncs_accounting_vote_clearance_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_accounting_vote_clearance_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_accounting_vote_clearance_id_seq OWNER TO rise_user;

--
-- Name: ncs_accounting_vote_clearance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_accounting_vote_clearance_id_seq OWNED BY public.ncs_accounting_vote_clearance.id;


--
-- Name: ncs_activity_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_activity_logs (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    created_by integer NOT NULL,
    action character varying(255) NOT NULL,
    log_type character varying(30) NOT NULL,
    log_type_title text NOT NULL,
    log_type_id integer DEFAULT 0 NOT NULL,
    changes text,
    log_for character varying(30) DEFAULT '0'::character varying NOT NULL,
    log_for_id integer DEFAULT 0 NOT NULL,
    log_for2 character varying(30) DEFAULT NULL::character varying,
    log_for_id2 integer,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_activity_logs OWNER TO postgres;

--
-- Name: ncs_activity_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_activity_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_activity_logs_id_seq OWNER TO postgres;

--
-- Name: ncs_activity_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_activity_logs_id_seq OWNED BY public.ncs_activity_logs.id;


--
-- Name: ncs_admin_appraisals; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_admin_appraisals (
    id integer NOT NULL,
    asset_tag character varying(50) NOT NULL,
    asset_name character varying(255) NOT NULL,
    asset_class character varying(100) NOT NULL,
    location character varying(100) NOT NULL,
    acquisition_date date NOT NULL,
    historical_cost numeric(15,2) DEFAULT 0.00,
    current_valuation numeric(15,2) DEFAULT 0.00,
    accumulated_depreciation numeric(15,2) DEFAULT 0.00,
    net_book_value numeric(15,2) DEFAULT 0.00,
    condition_rating character varying(40) DEFAULT 'Good'::character varying,
    valuation_date date DEFAULT CURRENT_DATE,
    valuer_name character varying(100) DEFAULT 'Chief Valuer & Surveyor'::character varying,
    land_title_status character varying(50) DEFAULT 'Titled (NCS Registered)'::character varying,
    insurance_status character varying(50) DEFAULT 'Fully Insured (NIC)'::character varying,
    remarks text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_admin_appraisals OWNER TO rise_user;

--
-- Name: ncs_admin_appraisals_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_admin_appraisals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_admin_appraisals_id_seq OWNER TO rise_user;

--
-- Name: ncs_admin_appraisals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_admin_appraisals_id_seq OWNED BY public.ncs_admin_appraisals.id;


--
-- Name: ncs_admin_approvals; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_admin_approvals (
    id integer NOT NULL,
    reference_no character varying(50) NOT NULL,
    approval_type character varying(50) NOT NULL,
    originating_department character varying(100) NOT NULL,
    originator_id integer DEFAULT 0,
    originator_name character varying(100) NOT NULL,
    title character varying(255) NOT NULL,
    amount numeric(15,2) DEFAULT 0.00,
    urgency character varying(30) DEFAULT 'Normal'::character varying,
    submission_date date DEFAULT CURRENT_DATE,
    current_rank_required integer DEFAULT 1,
    target_approver_id integer DEFAULT 0,
    target_approver_name character varying(100) DEFAULT 'General Secretary'::character varying,
    status character varying(50) DEFAULT 'Pending GS Vetting'::character varying,
    vetting_notes text,
    decision_date timestamp without time zone,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_admin_approvals OWNER TO rise_user;

--
-- Name: ncs_admin_approvals_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_admin_approvals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_admin_approvals_id_seq OWNER TO rise_user;

--
-- Name: ncs_admin_approvals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_admin_approvals_id_seq OWNED BY public.ncs_admin_approvals.id;


--
-- Name: ncs_admin_board_packages; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_admin_board_packages (
    id integer NOT NULL,
    package_ref character varying(50) NOT NULL,
    title character varying(255) NOT NULL,
    board_quarter character varying(50) NOT NULL,
    category character varying(50) NOT NULL,
    target_submission_date date NOT NULL,
    lead_author_name character varying(100) NOT NULL,
    security_level character varying(30) DEFAULT 'Internal Executive'::character varying,
    status character varying(50) DEFAULT 'Submitted to GS'::character varying,
    executive_summary text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_admin_board_packages OWNER TO rise_user;

--
-- Name: ncs_admin_board_packages_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_admin_board_packages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_admin_board_packages_id_seq OWNER TO rise_user;

--
-- Name: ncs_admin_board_packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_admin_board_packages_id_seq OWNED BY public.ncs_admin_board_packages.id;


--
-- Name: ncs_admin_federations; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_admin_federations (
    id integer NOT NULL,
    code character varying(20) NOT NULL,
    name character varying(255) NOT NULL,
    sport_category character varying(50) NOT NULL,
    governance_status character varying(50) DEFAULT 'Fully Compliant'::character varying,
    president_name character varying(100),
    general_secretary_name character varying(100),
    annual_grant_allocation numeric(15,2) DEFAULT 0.00,
    disbursed_ytd numeric(15,2) DEFAULT 0.00,
    statutory_returns_submitted character varying(30) DEFAULT 'Compliant (Q4 2026)'::character varying,
    international_affiliation character varying(100),
    last_audit_date date,
    compliance_score integer DEFAULT 85,
    notes text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_admin_federations OWNER TO rise_user;

--
-- Name: ncs_admin_federations_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_admin_federations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_admin_federations_id_seq OWNER TO rise_user;

--
-- Name: ncs_admin_federations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_admin_federations_id_seq OWNED BY public.ncs_admin_federations.id;


--
-- Name: ncs_announcements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_announcements (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    created_by integer NOT NULL,
    share_with text,
    created_at timestamp without time zone NOT NULL,
    files text NOT NULL,
    read_by text,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_announcements OWNER TO postgres;

--
-- Name: ncs_announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_announcements_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_announcements_id_seq OWNER TO postgres;

--
-- Name: ncs_announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_announcements_id_seq OWNED BY public.ncs_announcements.id;


--
-- Name: ncs_article_helpful_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_article_helpful_status (
    id integer NOT NULL,
    article_id integer NOT NULL,
    status character varying(255) NOT NULL,
    created_by integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_article_helpful_status OWNER TO postgres;

--
-- Name: ncs_article_helpful_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_article_helpful_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_article_helpful_status_id_seq OWNER TO postgres;

--
-- Name: ncs_article_helpful_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_article_helpful_status_id_seq OWNED BY public.ncs_article_helpful_status.id;


--
-- Name: ncs_asset_transaction_logs; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_asset_transaction_logs (
    id integer NOT NULL,
    asset_id integer NOT NULL,
    transaction_type character varying(64) NOT NULL,
    previous_val numeric(18,2) DEFAULT 0.00,
    new_val numeric(18,2) DEFAULT 0.00,
    notes text,
    performed_by integer DEFAULT 1,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_asset_transaction_logs OWNER TO rise_user;

--
-- Name: ncs_asset_transaction_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_asset_transaction_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_asset_transaction_logs_id_seq OWNER TO rise_user;

--
-- Name: ncs_asset_transaction_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_asset_transaction_logs_id_seq OWNED BY public.ncs_asset_transaction_logs.id;


--
-- Name: ncs_attendance; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_attendance (
    id integer NOT NULL,
    status character varying(255) DEFAULT 'incomplete'::character varying NOT NULL,
    user_id integer NOT NULL,
    in_time timestamp without time zone NOT NULL,
    out_time timestamp without time zone,
    checked_by integer,
    note text,
    checked_at timestamp without time zone,
    reject_reason text,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_attendance OWNER TO postgres;

--
-- Name: ncs_attendance_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_attendance_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_attendance_id_seq OWNER TO postgres;

--
-- Name: ncs_attendance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_attendance_id_seq OWNED BY public.ncs_attendance.id;


--
-- Name: ncs_audit_discrepancies; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_audit_discrepancies (
    id integer NOT NULL,
    discrepancy_code character varying(64) NOT NULL,
    entity_type character varying(64) NOT NULL,
    entity_id integer DEFAULT 0,
    entity_ref character varying(128) NOT NULL,
    severity character varying(32) DEFAULT 'MEDIUM'::character varying,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    financial_impact_ugx numeric(18,2) DEFAULT 0.00,
    raised_by integer DEFAULT 1,
    assigned_to integer DEFAULT 1,
    status character varying(32) DEFAULT 'OPEN'::character varying,
    management_response text,
    resolution_notes text,
    resolved_at timestamp without time zone,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_audit_discrepancies OWNER TO rise_user;

--
-- Name: ncs_audit_discrepancies_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_audit_discrepancies_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_audit_discrepancies_id_seq OWNER TO rise_user;

--
-- Name: ncs_audit_discrepancies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_audit_discrepancies_id_seq OWNED BY public.ncs_audit_discrepancies.id;


--
-- Name: ncs_audit_reports; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_audit_reports (
    id integer NOT NULL,
    report_code character varying(64) NOT NULL,
    report_title character varying(255) NOT NULL,
    financial_year character varying(32) DEFAULT 'FY 2026/2027'::character varying,
    quarter character varying(16) DEFAULT 'Q1'::character varying,
    audit_period character varying(128) DEFAULT 'Period Ended 30th September 2026'::character varying,
    overall_opinion character varying(64) DEFAULT 'SATISFACTORY'::character varying,
    summary_findings text NOT NULL,
    compiled_by integer DEFAULT 1,
    status character varying(32) DEFAULT 'SUBMITTED_TO_GS'::character varying,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_audit_reports OWNER TO rise_user;

--
-- Name: ncs_audit_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_audit_reports_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_audit_reports_id_seq OWNER TO rise_user;

--
-- Name: ncs_audit_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_audit_reports_id_seq OWNED BY public.ncs_audit_reports.id;


--
-- Name: ncs_automation_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_automation_settings (
    id integer NOT NULL,
    title text NOT NULL,
    matching_type character varying(255) NOT NULL,
    event_name text NOT NULL,
    conditions text NOT NULL,
    actions text NOT NULL,
    related_to character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_automation_settings OWNER TO postgres;

--
-- Name: ncs_automation_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_automation_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_automation_settings_id_seq OWNER TO postgres;

--
-- Name: ncs_automation_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_automation_settings_id_seq OWNED BY public.ncs_automation_settings.id;


--
-- Name: ncs_checklist_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_checklist_groups (
    id integer NOT NULL,
    title text NOT NULL,
    checklists text NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_checklist_groups OWNER TO postgres;

--
-- Name: ncs_checklist_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_checklist_groups_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_checklist_groups_id_seq OWNER TO postgres;

--
-- Name: ncs_checklist_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_checklist_groups_id_seq OWNED BY public.ncs_checklist_groups.id;


--
-- Name: ncs_checklist_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_checklist_items (
    id integer NOT NULL,
    title text NOT NULL,
    is_checked integer DEFAULT 0 NOT NULL,
    task_id integer DEFAULT 0 NOT NULL,
    sort double precision DEFAULT '1'::double precision NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_checklist_items OWNER TO postgres;

--
-- Name: ncs_checklist_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_checklist_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_checklist_items_id_seq OWNER TO postgres;

--
-- Name: ncs_checklist_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_checklist_items_id_seq OWNED BY public.ncs_checklist_items.id;


--
-- Name: ncs_checklist_template; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_checklist_template (
    id integer NOT NULL,
    title text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_checklist_template OWNER TO postgres;

--
-- Name: ncs_checklist_template_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_checklist_template_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_checklist_template_id_seq OWNER TO postgres;

--
-- Name: ncs_checklist_template_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_checklist_template_id_seq OWNED BY public.ncs_checklist_template.id;


--
-- Name: ncs_ci_sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_ci_sessions (
    id character varying(128) NOT NULL,
    ip_address character varying(45) NOT NULL,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    data bytea NOT NULL
);


ALTER TABLE public.ncs_ci_sessions OWNER TO postgres;

--
-- Name: ncs_client_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_client_groups (
    id integer NOT NULL,
    title text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_client_groups OWNER TO postgres;

--
-- Name: ncs_client_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_client_groups_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_client_groups_id_seq OWNER TO postgres;

--
-- Name: ncs_client_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_client_groups_id_seq OWNED BY public.ncs_client_groups.id;


--
-- Name: ncs_client_wallet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_client_wallet (
    id integer NOT NULL,
    amount double precision NOT NULL,
    payment_date date NOT NULL,
    note text,
    client_id integer NOT NULL,
    created_by integer DEFAULT 1,
    created_at timestamp without time zone,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_client_wallet OWNER TO postgres;

--
-- Name: ncs_client_wallet_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_client_wallet_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_client_wallet_id_seq OWNER TO postgres;

--
-- Name: ncs_client_wallet_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_client_wallet_id_seq OWNED BY public.ncs_client_wallet.id;


--
-- Name: ncs_clients; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_clients (
    id integer NOT NULL,
    company_name character varying(150) NOT NULL,
    type character varying(255) DEFAULT 'organization'::character varying NOT NULL,
    address text,
    city character varying(50) DEFAULT NULL::character varying,
    state character varying(50) DEFAULT NULL::character varying,
    zip character varying(50) DEFAULT NULL::character varying,
    country character varying(50) DEFAULT NULL::character varying,
    created_date timestamp without time zone,
    website text,
    phone character varying(20) DEFAULT NULL::character varying,
    currency_symbol character varying(20) DEFAULT NULL::character varying,
    starred_by text NOT NULL,
    group_ids text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    is_lead smallint DEFAULT '0'::smallint NOT NULL,
    lead_status_id integer NOT NULL,
    owner_id integer NOT NULL,
    created_by integer NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    lead_source_id integer NOT NULL,
    last_lead_status text NOT NULL,
    client_migration_date date,
    vat_number text,
    gst_number text,
    stripe_customer_id text NOT NULL,
    stripe_card_ending_digit integer NOT NULL,
    currency character varying(3) DEFAULT NULL::character varying,
    disable_online_payment smallint DEFAULT '0'::smallint NOT NULL,
    labels text,
    managers text NOT NULL
);


ALTER TABLE public.ncs_clients OWNER TO postgres;

--
-- Name: ncs_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_clients_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_clients_id_seq OWNER TO postgres;

--
-- Name: ncs_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_clients_id_seq OWNED BY public.ncs_clients.id;


--
-- Name: ncs_company; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_company (
    id integer NOT NULL,
    name text NOT NULL,
    address text NOT NULL,
    phone text NOT NULL,
    email text NOT NULL,
    website text NOT NULL,
    vat_number text NOT NULL,
    gst_number text NOT NULL,
    is_default smallint DEFAULT '0'::smallint NOT NULL,
    logo text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_company OWNER TO postgres;

--
-- Name: ncs_company_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_company_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_company_id_seq OWNER TO postgres;

--
-- Name: ncs_company_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_company_id_seq OWNED BY public.ncs_company.id;


--
-- Name: ncs_contract_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_contract_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    contract_id integer NOT NULL,
    item_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_contract_items OWNER TO postgres;

--
-- Name: ncs_contract_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_contract_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_contract_items_id_seq OWNER TO postgres;

--
-- Name: ncs_contract_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_contract_items_id_seq OWNED BY public.ncs_contract_items.id;


--
-- Name: ncs_contract_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_contract_templates (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    template text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_contract_templates OWNER TO postgres;

--
-- Name: ncs_contract_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_contract_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_contract_templates_id_seq OWNER TO postgres;

--
-- Name: ncs_contract_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_contract_templates_id_seq OWNED BY public.ncs_contract_templates.id;


--
-- Name: ncs_contracts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_contracts (
    id integer NOT NULL,
    title text NOT NULL,
    client_id integer NOT NULL,
    project_id integer NOT NULL,
    contract_date date NOT NULL,
    valid_until date NOT NULL,
    note text,
    last_email_sent_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    discount_type character varying(255) NOT NULL,
    discount_amount double precision NOT NULL,
    discount_amount_type character varying(255) NOT NULL,
    content text NOT NULL,
    public_key character varying(10) NOT NULL,
    accepted_by integer DEFAULT 0 NOT NULL,
    staff_signed_by integer DEFAULT 0 NOT NULL,
    meta_data text NOT NULL,
    files text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_contracts OWNER TO postgres;

--
-- Name: ncs_contracts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_contracts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_contracts_id_seq OWNER TO postgres;

--
-- Name: ncs_contracts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_contracts_id_seq OWNED BY public.ncs_contracts.id;


--
-- Name: ncs_custom_field_values; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_custom_field_values (
    id integer NOT NULL,
    related_to_type character varying(50) NOT NULL,
    related_to_id integer NOT NULL,
    custom_field_id integer NOT NULL,
    value text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_custom_field_values OWNER TO postgres;

--
-- Name: ncs_custom_field_values_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_custom_field_values_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_custom_field_values_id_seq OWNER TO postgres;

--
-- Name: ncs_custom_field_values_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_custom_field_values_id_seq OWNED BY public.ncs_custom_field_values.id;


--
-- Name: ncs_custom_fields; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_custom_fields (
    id integer NOT NULL,
    title text NOT NULL,
    title_language_key text NOT NULL,
    placeholder_language_key text NOT NULL,
    show_in_embedded_form smallint DEFAULT '0'::smallint NOT NULL,
    placeholder text NOT NULL,
    template_variable_name text,
    options text NOT NULL,
    field_type character varying(50) NOT NULL,
    related_to character varying(50) NOT NULL,
    sort integer NOT NULL,
    required smallint DEFAULT '0'::smallint NOT NULL,
    add_filter smallint DEFAULT '0'::smallint NOT NULL,
    show_in_table smallint DEFAULT '0'::smallint NOT NULL,
    show_in_invoice smallint DEFAULT '0'::smallint NOT NULL,
    show_in_estimate smallint DEFAULT '0'::smallint NOT NULL,
    show_in_contract smallint DEFAULT '0'::smallint NOT NULL,
    show_in_order smallint DEFAULT '0'::smallint NOT NULL,
    show_in_proposal smallint DEFAULT '0'::smallint NOT NULL,
    visible_to_admins_only smallint DEFAULT '0'::smallint NOT NULL,
    hide_from_clients smallint DEFAULT '0'::smallint NOT NULL,
    disable_editing_by_clients smallint DEFAULT '0'::smallint NOT NULL,
    show_on_kanban_card smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    show_in_subscription smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_custom_fields OWNER TO postgres;

--
-- Name: ncs_custom_fields_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_custom_fields_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_custom_fields_id_seq OWNER TO postgres;

--
-- Name: ncs_custom_fields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_custom_fields_id_seq OWNED BY public.ncs_custom_fields.id;


--
-- Name: ncs_custom_widgets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_custom_widgets (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title text,
    content text,
    show_title smallint DEFAULT '0'::smallint NOT NULL,
    show_border smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_custom_widgets OWNER TO postgres;

--
-- Name: ncs_custom_widgets_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_custom_widgets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_custom_widgets_id_seq OWNER TO postgres;

--
-- Name: ncs_custom_widgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_custom_widgets_id_seq OWNED BY public.ncs_custom_widgets.id;


--
-- Name: ncs_dashboards; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_dashboards (
    id integer NOT NULL,
    user_id integer NOT NULL,
    title text,
    data text,
    color character varying(15) NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_dashboards OWNER TO postgres;

--
-- Name: ncs_dashboards_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_dashboards_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_dashboards_id_seq OWNER TO postgres;

--
-- Name: ncs_dashboards_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_dashboards_id_seq OWNED BY public.ncs_dashboards.id;


--
-- Name: ncs_departments; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_departments (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    code character varying(50) DEFAULT NULL::character varying,
    description text,
    head_id integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    created_by integer DEFAULT 0,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_departments OWNER TO rise_user;

--
-- Name: ncs_departments_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_departments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_departments_id_seq OWNER TO rise_user;

--
-- Name: ncs_departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_departments_id_seq OWNED BY public.ncs_departments.id;


--
-- Name: ncs_e_invoice_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_e_invoice_templates (
    id integer NOT NULL,
    title text NOT NULL,
    template text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_e_invoice_templates OWNER TO postgres;

--
-- Name: ncs_e_invoice_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_e_invoice_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_e_invoice_templates_id_seq OWNER TO postgres;

--
-- Name: ncs_e_invoice_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_e_invoice_templates_id_seq OWNED BY public.ncs_e_invoice_templates.id;


--
-- Name: ncs_email_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_email_templates (
    id integer NOT NULL,
    template_name character varying(50) NOT NULL,
    email_subject text NOT NULL,
    default_message text NOT NULL,
    custom_message text,
    template_type character varying(255) DEFAULT 'default'::character varying NOT NULL,
    language character varying(50) NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_email_templates OWNER TO postgres;

--
-- Name: ncs_email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_email_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_email_templates_id_seq OWNER TO postgres;

--
-- Name: ncs_email_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_email_templates_id_seq OWNED BY public.ncs_email_templates.id;


--
-- Name: ncs_engineering_assets; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_assets (
    id integer NOT NULL,
    asset_code character varying(100) NOT NULL,
    asset_name character varying(255) NOT NULL,
    category character varying(50) NOT NULL,
    facility_location character varying(255) NOT NULL,
    condition_rating character varying(30) DEFAULT 'GOOD'::character varying,
    purchase_value numeric(18,2) DEFAULT 0.00,
    last_inspection_date date,
    next_maintenance_date date,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_assets OWNER TO rise_user;

--
-- Name: ncs_engineering_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_assets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_assets_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_assets_id_seq OWNED BY public.ncs_engineering_assets.id;


--
-- Name: ncs_engineering_capex; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_capex (
    id integer NOT NULL,
    capex_ref_no character varying(100) NOT NULL,
    project_title character varying(255) NOT NULL,
    facility_location character varying(255) NOT NULL,
    estimated_budget numeric(18,2) NOT NULL,
    justification text NOT NULL,
    department_id integer DEFAULT 7,
    requested_by integer NOT NULL,
    assigned_approver_id integer DEFAULT 0,
    status character varying(50) DEFAULT 'PENDING_HOD'::character varying,
    hod_user_id integer,
    hod_comments text,
    hod_decided_at timestamp without time zone,
    gs_user_id integer,
    gs_comments text,
    gs_decided_at timestamp without time zone,
    workflow_history text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_capex OWNER TO rise_user;

--
-- Name: ncs_engineering_capex_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_capex_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_capex_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_capex_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_capex_id_seq OWNED BY public.ncs_engineering_capex.id;


--
-- Name: ncs_engineering_civil_assets; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_civil_assets (
    id integer NOT NULL,
    asset_number character varying(100) NOT NULL,
    tag_number character varying(100) NOT NULL,
    asset_description text NOT NULL,
    category_segment3 character varying(50) NOT NULL,
    fb_cost numeric(18,2) NOT NULL,
    adjusted_cost numeric(18,2) NOT NULL,
    useful_life_years integer DEFAULT 0,
    verification_status character varying(30) DEFAULT 'UNVERIFIED'::character varying,
    verified_by integer,
    verification_date date,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_civil_assets OWNER TO rise_user;

--
-- Name: ncs_engineering_civil_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_civil_assets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_civil_assets_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_civil_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_civil_assets_id_seq OWNED BY public.ncs_engineering_civil_assets.id;


--
-- Name: ncs_engineering_electrical_assets; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_electrical_assets (
    id integer NOT NULL,
    asset_number character varying(100) NOT NULL,
    tag_number character varying(100) NOT NULL,
    asset_description text NOT NULL,
    category_segment3 character varying(50) DEFAULT 'ELECTRICAL MACHINERY'::character varying,
    fb_cost numeric(18,2) NOT NULL,
    adjusted_cost numeric(18,2) NOT NULL,
    useful_life_years integer DEFAULT 5,
    fuel_level_percent numeric(5,2) DEFAULT 100.00,
    running_hours numeric(10,2) DEFAULT 0.00,
    verification_status character varying(30) DEFAULT 'VERIFIED'::character varying,
    last_service_date date,
    next_service_date date,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_electrical_assets OWNER TO rise_user;

--
-- Name: ncs_engineering_electrical_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_electrical_assets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_electrical_assets_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_electrical_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_electrical_assets_id_seq OWNED BY public.ncs_engineering_electrical_assets.id;


--
-- Name: ncs_engineering_inspections; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_inspections (
    id integer NOT NULL,
    inspection_code character varying(100) NOT NULL,
    event_or_facility character varying(255) NOT NULL,
    inspection_date date NOT NULL,
    inspector_user_id integer NOT NULL,
    civil_safety_status character varying(30) DEFAULT 'PASS'::character varying,
    electrical_safety_status character varying(30) DEFAULT 'PASS'::character varying,
    readiness_score numeric(5,2) DEFAULT 100.00,
    findings text,
    action_items text,
    status character varying(30) DEFAULT 'PASSED'::character varying,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_inspections OWNER TO rise_user;

--
-- Name: ncs_engineering_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_inspections_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_inspections_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_inspections_id_seq OWNED BY public.ncs_engineering_inspections.id;


--
-- Name: ncs_engineering_technicians; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_technicians (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    trade_specialization character varying(50) NOT NULL,
    trade_group character varying(20) NOT NULL,
    phone character varying(32) NOT NULL,
    email character varying(128),
    status character varying(30) DEFAULT 'AVAILABLE'::character varying,
    assigned_venue character varying(255),
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_technicians OWNER TO rise_user;

--
-- Name: ncs_engineering_technicians_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_technicians_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_technicians_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_technicians_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_technicians_id_seq OWNED BY public.ncs_engineering_technicians.id;


--
-- Name: ncs_engineering_work_orders; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_engineering_work_orders (
    id integer NOT NULL,
    wo_number character varying(100) NOT NULL,
    title character varying(255) NOT NULL,
    category character varying(50) NOT NULL,
    priority character varying(20) DEFAULT 'MEDIUM'::character varying,
    facility_location character varying(255) NOT NULL,
    description text NOT NULL,
    assigned_to integer,
    requested_by integer NOT NULL,
    estimated_cost numeric(15,2) DEFAULT 0.00,
    status character varying(30) DEFAULT 'PENDING'::character varying,
    completion_date date,
    remarks text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_engineering_work_orders OWNER TO rise_user;

--
-- Name: ncs_engineering_work_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_engineering_work_orders_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_engineering_work_orders_id_seq OWNER TO rise_user;

--
-- Name: ncs_engineering_work_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_engineering_work_orders_id_seq OWNED BY public.ncs_engineering_work_orders.id;


--
-- Name: ncs_estimate_comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_estimate_comments (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    description text NOT NULL,
    estimate_id integer DEFAULT 0 NOT NULL,
    files text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_estimate_comments OWNER TO postgres;

--
-- Name: ncs_estimate_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_estimate_comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_estimate_comments_id_seq OWNER TO postgres;

--
-- Name: ncs_estimate_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_estimate_comments_id_seq OWNED BY public.ncs_estimate_comments.id;


--
-- Name: ncs_estimate_forms; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_estimate_forms (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    status character varying(255) NOT NULL,
    assigned_to integer NOT NULL,
    public smallint DEFAULT '0'::smallint NOT NULL,
    enable_attachment smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_estimate_forms OWNER TO postgres;

--
-- Name: ncs_estimate_forms_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_estimate_forms_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_estimate_forms_id_seq OWNER TO postgres;

--
-- Name: ncs_estimate_forms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_estimate_forms_id_seq OWNED BY public.ncs_estimate_forms.id;


--
-- Name: ncs_estimate_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_estimate_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    estimate_id integer NOT NULL,
    item_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_estimate_items OWNER TO postgres;

--
-- Name: ncs_estimate_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_estimate_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_estimate_items_id_seq OWNER TO postgres;

--
-- Name: ncs_estimate_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_estimate_items_id_seq OWNED BY public.ncs_estimate_items.id;


--
-- Name: ncs_estimate_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_estimate_requests (
    id integer NOT NULL,
    estimate_form_id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    client_id integer NOT NULL,
    lead_id integer NOT NULL,
    assigned_to integer NOT NULL,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    files text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_estimate_requests OWNER TO postgres;

--
-- Name: ncs_estimate_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_estimate_requests_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_estimate_requests_id_seq OWNER TO postgres;

--
-- Name: ncs_estimate_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_estimate_requests_id_seq OWNED BY public.ncs_estimate_requests.id;


--
-- Name: ncs_estimates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_estimates (
    id integer NOT NULL,
    client_id integer NOT NULL,
    estimate_request_id integer DEFAULT 0 NOT NULL,
    estimate_date date NOT NULL,
    valid_until date NOT NULL,
    note text,
    last_email_sent_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    discount_type character varying(255) NOT NULL,
    discount_amount double precision NOT NULL,
    discount_amount_type character varying(255) NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    accepted_by integer DEFAULT 0 NOT NULL,
    meta_data text NOT NULL,
    created_by integer NOT NULL,
    signature text NOT NULL,
    public_key text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_estimates OWNER TO postgres;

--
-- Name: ncs_estimates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_estimates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_estimates_id_seq OWNER TO postgres;

--
-- Name: ncs_estimates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_estimates_id_seq OWNED BY public.ncs_estimates.id;


--
-- Name: ncs_event_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_event_tracker (
    id integer NOT NULL,
    event_type character varying(255) NOT NULL,
    context character varying(255) NOT NULL,
    context_id integer NOT NULL,
    read_count integer,
    status character varying(255) DEFAULT 'new'::character varying,
    last_read_time timestamp without time zone,
    created_at timestamp without time zone NOT NULL,
    logs text,
    random_id character varying(10) NOT NULL,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_event_tracker OWNER TO postgres;

--
-- Name: ncs_event_tracker_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_event_tracker_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_event_tracker_id_seq OWNER TO postgres;

--
-- Name: ncs_event_tracker_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_event_tracker_id_seq OWNED BY public.ncs_event_tracker.id;


--
-- Name: ncs_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_events (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    start_date date NOT NULL,
    end_date date,
    start_time time without time zone,
    end_time time without time zone,
    created_by integer NOT NULL,
    location text,
    client_id integer DEFAULT 0 NOT NULL,
    labels text NOT NULL,
    share_with text,
    editable_google_event smallint DEFAULT '0'::smallint NOT NULL,
    google_event_id text NOT NULL,
    deleted integer DEFAULT 0 NOT NULL,
    lead_id integer DEFAULT 0 NOT NULL,
    ticket_id integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    task_id integer DEFAULT 0 NOT NULL,
    proposal_id integer DEFAULT 0 NOT NULL,
    contract_id integer DEFAULT 0 NOT NULL,
    subscription_id integer DEFAULT 0 NOT NULL,
    invoice_id integer DEFAULT 0 NOT NULL,
    order_id integer DEFAULT 0 NOT NULL,
    estimate_id integer DEFAULT 0 NOT NULL,
    related_user_id integer DEFAULT 0 NOT NULL,
    next_recurring_time timestamp without time zone,
    no_of_cycles_completed integer DEFAULT 0 NOT NULL,
    snoozing_time timestamp without time zone,
    reminder_status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    type character varying(255) DEFAULT 'event'::character varying NOT NULL,
    color character varying(15) NOT NULL,
    recurring integer DEFAULT 0 NOT NULL,
    repeat_every integer DEFAULT 0 NOT NULL,
    repeat_type character varying(255) DEFAULT NULL::character varying,
    no_of_cycles integer DEFAULT 0 NOT NULL,
    last_start_date date,
    recurring_dates text NOT NULL,
    confirmed_by text NOT NULL,
    rejected_by text NOT NULL,
    files text NOT NULL,
    event_id integer DEFAULT 0
);


ALTER TABLE public.ncs_events OWNER TO postgres;

--
-- Name: ncs_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_events_id_seq OWNER TO postgres;

--
-- Name: ncs_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_events_id_seq OWNED BY public.ncs_events.id;


--
-- Name: ncs_expense_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_expense_categories (
    id integer NOT NULL,
    title text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_expense_categories OWNER TO postgres;

--
-- Name: ncs_expense_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_expense_categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_expense_categories_id_seq OWNER TO postgres;

--
-- Name: ncs_expense_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_expense_categories_id_seq OWNED BY public.ncs_expense_categories.id;


--
-- Name: ncs_expenses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_expenses (
    id integer NOT NULL,
    expense_date date NOT NULL,
    category_id integer NOT NULL,
    description text,
    amount double precision NOT NULL,
    files text NOT NULL,
    title text NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    client_id integer DEFAULT 0 NOT NULL,
    recurring smallint DEFAULT '0'::smallint NOT NULL,
    recurring_expense_id smallint DEFAULT '0'::smallint NOT NULL,
    repeat_every integer DEFAULT 0 NOT NULL,
    repeat_type character varying(255) DEFAULT NULL::character varying,
    no_of_cycles integer DEFAULT 0 NOT NULL,
    next_recurring_date date,
    no_of_cycles_completed integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    created_by integer NOT NULL
);


ALTER TABLE public.ncs_expenses OWNER TO postgres;

--
-- Name: ncs_expenses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_expenses_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_expenses_id_seq OWNER TO postgres;

--
-- Name: ncs_expenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_expenses_id_seq OWNED BY public.ncs_expenses.id;


--
-- Name: ncs_facilities; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_facilities (
    id integer NOT NULL,
    facility_code character varying(50) NOT NULL,
    title character varying(255) NOT NULL,
    category character varying(100) NOT NULL,
    location character varying(100) NOT NULL,
    capacity integer DEFAULT 0,
    ntr_rate_per_day numeric(15,2) DEFAULT 0.00,
    caution_deposit_rate numeric(15,2) DEFAULT 0.00,
    status character varying(50) DEFAULT 'Operational'::character varying,
    description text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_facilities OWNER TO rise_user;

--
-- Name: ncs_facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_facilities_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_facilities_id_seq OWNER TO rise_user;

--
-- Name: ncs_facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_facilities_id_seq OWNED BY public.ncs_facilities.id;


--
-- Name: ncs_facility_bookings; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_facility_bookings (
    id integer NOT NULL,
    booking_reference character varying(50) NOT NULL,
    facility_id integer NOT NULL,
    client_name character varying(255) NOT NULL,
    client_type character varying(50) NOT NULL,
    event_title character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    tariff_category character varying(50) DEFAULT 'Commercial Standard'::character varying,
    total_fee_ugx numeric(15,2) DEFAULT 0.00,
    caution_deposit_ugx numeric(15,2) DEFAULT 0.00,
    payment_status character varying(30) DEFAULT 'PENDING'::character varying,
    technical_approval character varying(30) DEFAULT 'APPROVED'::character varying,
    notes text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_facility_bookings OWNER TO rise_user;

--
-- Name: ncs_facility_bookings_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_facility_bookings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_facility_bookings_id_seq OWNER TO rise_user;

--
-- Name: ncs_facility_bookings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_facility_bookings_id_seq OWNED BY public.ncs_facility_bookings.id;


--
-- Name: ncs_facility_inspections; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_facility_inspections (
    id integer NOT NULL,
    booking_id integer NOT NULL,
    inspection_type character varying(50) NOT NULL,
    inspector_name character varying(100) NOT NULL,
    safety_cleared integer DEFAULT 1,
    damage_deduction_ugx numeric(15,2) DEFAULT 0.00,
    deposit_refund_status character varying(50) DEFAULT 'Cleared for Refund'::character varying,
    remarks text,
    inspection_date date DEFAULT CURRENT_DATE,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_facility_inspections OWNER TO rise_user;

--
-- Name: ncs_facility_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_facility_inspections_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_facility_inspections_id_seq OWNER TO rise_user;

--
-- Name: ncs_facility_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_facility_inspections_id_seq OWNED BY public.ncs_facility_inspections.id;


--
-- Name: ncs_file_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_file_category (
    id integer NOT NULL,
    name text,
    type character varying(255) DEFAULT 'project'::character varying NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_file_category OWNER TO postgres;

--
-- Name: ncs_file_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_file_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_file_category_id_seq OWNER TO postgres;

--
-- Name: ncs_file_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_file_category_id_seq OWNED BY public.ncs_file_category.id;


--
-- Name: ncs_fixed_assets; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_fixed_assets (
    id integer NOT NULL,
    interface_line_number character varying(128),
    asset_book character varying(128) DEFAULT 'NCS FA BOOK'::character varying,
    asset_number character varying(128),
    tag_number character varying(128),
    asset_description text NOT NULL,
    category_segment1 character varying(128) NOT NULL,
    category_segment3 character varying(128) NOT NULL,
    category_segment4 character varying(128) NOT NULL,
    asset_units integer DEFAULT 1,
    fb_cost numeric(18,2) DEFAULT 0.00,
    adjusted_cost numeric(18,2) DEFAULT 0.00,
    date_placed_in_service date,
    custodian_department character varying(128) DEFAULT 'General Administration'::character varying,
    location_building character varying(128) DEFAULT 'NCS Lugogo Head Office'::character varying,
    location_room character varying(64) DEFAULT ''::character varying,
    depreciation_method character varying(32) DEFAULT 'STRAIGHT_LINE'::character varying,
    useful_life_years integer DEFAULT 5,
    accumulated_depreciation numeric(18,2) DEFAULT 0.00,
    net_book_value numeric(18,2) DEFAULT 0.00,
    status character varying(32) DEFAULT 'ACTIVE'::character varying,
    verification_status character varying(32) DEFAULT 'UNVERIFIED'::character varying,
    last_verified_at timestamp without time zone,
    worksheet_source character varying(128),
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_fixed_assets OWNER TO rise_user;

--
-- Name: ncs_fixed_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_fixed_assets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_fixed_assets_id_seq OWNER TO rise_user;

--
-- Name: ncs_fixed_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_fixed_assets_id_seq OWNED BY public.ncs_fixed_assets.id;


--
-- Name: ncs_fleet_routes; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_fleet_routes (
    id integer NOT NULL,
    title character varying(255) DEFAULT ''::character varying NOT NULL,
    vehicle_id integer DEFAULT 0,
    assigned_driver integer DEFAULT 0,
    start_location character varying(255) DEFAULT ''::character varying,
    end_location character varying(255) DEFAULT ''::character varying,
    waypoints text DEFAULT ''::text,
    distance_km numeric(10,2) DEFAULT 0,
    estimated_duration character varying(50) DEFAULT ''::character varying,
    status character varying(50) DEFAULT 'planned'::character varying,
    scheduled_date date,
    departure_time time without time zone,
    notes text DEFAULT ''::text,
    created_by integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    deleted smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_fleet_routes OWNER TO rise_user;

--
-- Name: ncs_fleet_routes_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_fleet_routes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_fleet_routes_id_seq OWNER TO rise_user;

--
-- Name: ncs_fleet_routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_fleet_routes_id_seq OWNED BY public.ncs_fleet_routes.id;


--
-- Name: ncs_fleet_service_logs; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_fleet_service_logs (
    id integer NOT NULL,
    vehicle_id integer DEFAULT 0 NOT NULL,
    service_type character varying(100) DEFAULT 'other'::character varying NOT NULL,
    service_date date DEFAULT CURRENT_DATE NOT NULL,
    mileage_at_service integer DEFAULT 0,
    cost numeric(10,2) DEFAULT 0,
    service_provider character varying(255) DEFAULT ''::character varying,
    next_service_date date,
    next_service_mileage integer DEFAULT 0,
    description text DEFAULT ''::text,
    created_by integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    deleted smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_fleet_service_logs OWNER TO rise_user;

--
-- Name: ncs_fleet_service_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_fleet_service_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_fleet_service_logs_id_seq OWNER TO rise_user;

--
-- Name: ncs_fleet_service_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_fleet_service_logs_id_seq OWNED BY public.ncs_fleet_service_logs.id;


--
-- Name: ncs_fleet_vehicles; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_fleet_vehicles (
    id integer NOT NULL,
    vin character varying(17) DEFAULT ''::character varying NOT NULL,
    plate_number character varying(20) DEFAULT ''::character varying NOT NULL,
    make character varying(100) DEFAULT ''::character varying NOT NULL,
    model character varying(100) DEFAULT ''::character varying NOT NULL,
    year integer DEFAULT 0 NOT NULL,
    color character varying(50) DEFAULT ''::character varying,
    fuel_type character varying(50) DEFAULT 'petrol'::character varying,
    status character varying(50) DEFAULT 'available'::character varying,
    assigned_driver integer DEFAULT 0,
    current_route_id integer DEFAULT 0,
    mileage integer DEFAULT 0,
    last_service_date date,
    next_service_date date,
    notes text DEFAULT ''::text,
    created_by integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_fleet_vehicles OWNER TO rise_user;

--
-- Name: ncs_fleet_vehicles_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_fleet_vehicles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_fleet_vehicles_id_seq OWNER TO rise_user;

--
-- Name: ncs_fleet_vehicles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_fleet_vehicles_id_seq OWNED BY public.ncs_fleet_vehicles.id;


--
-- Name: ncs_folders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_folders (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    folder_id character varying(255) NOT NULL,
    parent_id integer NOT NULL,
    level text,
    created_by integer,
    created_at timestamp without time zone,
    permissions text,
    context character varying(255) NOT NULL,
    context_id integer NOT NULL,
    starred_by text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_folders OWNER TO postgres;

--
-- Name: ncs_folders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_folders_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_folders_id_seq OWNER TO postgres;

--
-- Name: ncs_folders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_folders_id_seq OWNED BY public.ncs_folders.id;


--
-- Name: ncs_general_files; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_general_files (
    id integer NOT NULL,
    file_name text NOT NULL,
    file_id text,
    service_type character varying(20) DEFAULT NULL::character varying,
    description text,
    file_size double precision NOT NULL,
    created_at timestamp without time zone NOT NULL,
    client_id integer DEFAULT 0 NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    uploaded_by integer NOT NULL,
    folder_id integer DEFAULT 0,
    context character varying(100) NOT NULL,
    context_id integer DEFAULT 0,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_general_files OWNER TO postgres;

--
-- Name: ncs_general_files_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_general_files_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_general_files_id_seq OWNER TO postgres;

--
-- Name: ncs_general_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_general_files_id_seq OWNED BY public.ncs_general_files.id;


--
-- Name: ncs_help_articles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_help_articles (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    category_id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    files text NOT NULL,
    total_views integer DEFAULT 0 NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    labels text,
    related_article_ids text
);


ALTER TABLE public.ncs_help_articles OWNER TO postgres;

--
-- Name: ncs_help_articles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_help_articles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_help_articles_id_seq OWNER TO postgres;

--
-- Name: ncs_help_articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_help_articles_id_seq OWNED BY public.ncs_help_articles.id;


--
-- Name: ncs_help_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_help_categories (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    type character varying(255) NOT NULL,
    sort integer NOT NULL,
    articles_order character varying(3) DEFAULT ''::character varying NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    related_articles text,
    banner_image text NOT NULL,
    banner_url text
);


ALTER TABLE public.ncs_help_categories OWNER TO postgres;

--
-- Name: ncs_help_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_help_categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_help_categories_id_seq OWNER TO postgres;

--
-- Name: ncs_help_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_help_categories_id_seq OWNED BY public.ncs_help_categories.id;


--
-- Name: ncs_hostel_occupancies; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hostel_occupancies (
    id integer NOT NULL,
    room_number character varying(30) NOT NULL,
    athlete_name character varying(128) NOT NULL,
    nin_or_passport character varying(64),
    federation_name character varying(128) NOT NULL,
    gender character varying(20) DEFAULT 'Male'::character varying,
    check_in_date date NOT NULL,
    check_out_date date NOT NULL,
    status character varying(30) DEFAULT 'Active Camp'::character varying,
    key_issued integer DEFAULT 1,
    notes text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_hostel_occupancies OWNER TO rise_user;

--
-- Name: ncs_hostel_occupancies_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hostel_occupancies_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hostel_occupancies_id_seq OWNER TO rise_user;

--
-- Name: ncs_hostel_occupancies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hostel_occupancies_id_seq OWNED BY public.ncs_hostel_occupancies.id;


--
-- Name: ncs_hr_appraisal_items; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_appraisal_items (
    id integer NOT NULL,
    appraisal_id integer NOT NULL,
    section character varying(32) NOT NULL,
    kpi_description text NOT NULL,
    target text,
    achievement text,
    weight numeric(5,2) DEFAULT 0,
    self_score numeric(3,1) DEFAULT 0,
    supervisor_score numeric(3,1) DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_appraisal_items OWNER TO rise_user;

--
-- Name: ncs_hr_appraisal_items_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_appraisal_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_appraisal_items_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_appraisal_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_appraisal_items_id_seq OWNED BY public.ncs_hr_appraisal_items.id;


--
-- Name: ncs_hr_appraisals; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_appraisals (
    id integer NOT NULL,
    period_name character varying(128) NOT NULL,
    period_year smallint NOT NULL,
    employee_id integer NOT NULL,
    supervisor_id integer,
    kpi_self_score numeric(5,2) DEFAULT 0,
    kpi_supervisor_score numeric(5,2) DEFAULT 0,
    competency_self_score numeric(5,2) DEFAULT 0,
    competency_supervisor_score numeric(5,2) DEFAULT 0,
    behavioral_self_score numeric(5,2) DEFAULT 0,
    behavioral_supervisor_score numeric(5,2) DEFAULT 0,
    innovation_self_score numeric(5,2) DEFAULT 0,
    innovation_supervisor_score numeric(5,2) DEFAULT 0,
    final_score numeric(5,2) DEFAULT 0,
    performance_rating character varying(32),
    self_comments text,
    supervisor_comments text,
    hr_comments text,
    employee_feedback text,
    status character varying(32) DEFAULT 'pending'::character varying,
    self_submitted_at timestamp without time zone,
    supervisor_reviewed_at timestamp without time zone,
    hr_moderated_at timestamp without time zone,
    completed_at timestamp without time zone,
    created_by integer,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_appraisals OWNER TO rise_user;

--
-- Name: ncs_hr_appraisals_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_appraisals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_appraisals_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_appraisals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_appraisals_id_seq OWNED BY public.ncs_hr_appraisals.id;


--
-- Name: ncs_hr_memo_recipients; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_memo_recipients (
    id integer NOT NULL,
    memo_id integer NOT NULL,
    recipient_id integer NOT NULL,
    read_at timestamp without time zone,
    response text,
    responded_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_memo_recipients OWNER TO rise_user;

--
-- Name: ncs_hr_memo_recipients_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_memo_recipients_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_memo_recipients_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_memo_recipients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_memo_recipients_id_seq OWNED BY public.ncs_hr_memo_recipients.id;


--
-- Name: ncs_hr_memos; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_memos (
    id integer NOT NULL,
    memo_number character varying(64) NOT NULL,
    sender_id integer NOT NULL,
    subject character varying(255) NOT NULL,
    content text NOT NULL,
    priority character varying(16) DEFAULT 'routine'::character varying,
    target_type character varying(32) DEFAULT 'specific'::character varying,
    target_department character varying(64),
    is_confidential smallint DEFAULT 0,
    created_by integer,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_memos OWNER TO rise_user;

--
-- Name: ncs_hr_memos_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_memos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_memos_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_memos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_memos_id_seq OWNED BY public.ncs_hr_memos.id;


--
-- Name: ncs_hr_payroll; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_payroll (
    id integer NOT NULL,
    period_month smallint NOT NULL,
    period_year smallint NOT NULL,
    user_id integer NOT NULL,
    basic_salary numeric(14,2) DEFAULT 0,
    housing_allowance numeric(14,2) DEFAULT 0,
    transport_allowance numeric(14,2) DEFAULT 0,
    responsibility_allowance numeric(14,2) DEFAULT 0,
    overtime_allowance numeric(14,2) DEFAULT 0,
    field_honorarium numeric(14,2) DEFAULT 0,
    medical_allowance numeric(14,2) DEFAULT 0,
    sitting_allowance numeric(14,2) DEFAULT 0,
    other_allowances numeric(14,2) DEFAULT 0,
    gross_salary numeric(14,2) DEFAULT 0,
    paye_tax numeric(14,2) DEFAULT 0,
    nssf_employee numeric(14,2) DEFAULT 0,
    nssf_employer numeric(14,2) DEFAULT 0,
    lst_deduction numeric(14,2) DEFAULT 0,
    sacco_savings numeric(14,2) DEFAULT 0,
    sacco_loan numeric(14,2) DEFAULT 0,
    bank_loan numeric(14,2) DEFAULT 0,
    salary_advance numeric(14,2) DEFAULT 0,
    union_dues numeric(14,2) DEFAULT 0,
    other_deductions numeric(14,2) DEFAULT 0,
    total_deductions numeric(14,2) DEFAULT 0,
    net_salary numeric(14,2) DEFAULT 0,
    status character varying(32) DEFAULT 'draft'::character varying,
    approved_by integer,
    approved_at timestamp without time zone,
    notes text,
    created_by integer,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_payroll OWNER TO rise_user;

--
-- Name: ncs_hr_payroll_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_payroll_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_payroll_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_payroll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_payroll_id_seq OWNED BY public.ncs_hr_payroll.id;


--
-- Name: ncs_hr_profiles; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_profiles (
    id integer NOT NULL,
    user_id integer NOT NULL,
    staff_id character varying(32),
    nin_number character varying(32),
    passport_number character varying(32),
    gender character varying(16),
    marital_status character varying(32),
    disability_status smallint DEFAULT 0,
    home_district character varying(64),
    residential_address text,
    next_of_kin_name character varying(128),
    next_of_kin_phone character varying(32),
    next_of_kin_relationship character varying(32),
    next_of_kin_nin character varying(32),
    emergency_contact_name character varying(128),
    emergency_contact_phone character varying(32),
    emergency_contact2_name character varying(128),
    emergency_contact2_phone character varying(32),
    department character varying(64),
    duty_station character varying(128) DEFAULT 'NCS Lugogo Head Office'::character varying,
    employment_terms character varying(32),
    salary_scale character varying(16),
    direct_supervisor_id integer,
    tin_number character varying(32),
    nssf_number character varying(32),
    bank_name character varying(64),
    bank_branch character varying(64),
    bank_account_number character varying(64),
    bank_account_name character varying(128),
    appointment_date date,
    probation_end_date date,
    contract_expiry_date date,
    notes text,
    created_by integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_profiles OWNER TO rise_user;

--
-- Name: ncs_hr_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_profiles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_profiles_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_profiles_id_seq OWNED BY public.ncs_hr_profiles.id;


--
-- Name: ncs_hr_report_submissions; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_report_submissions (
    id integer NOT NULL,
    template_id integer NOT NULL,
    department character varying(64) NOT NULL,
    period_label character varying(128) NOT NULL,
    submission_data text,
    status character varying(32) DEFAULT 'submitted'::character varying,
    hod_approved_by integer,
    hod_approved_at timestamp without time zone,
    notes text,
    submitted_by integer NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_report_submissions OWNER TO rise_user;

--
-- Name: ncs_hr_report_submissions_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_report_submissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_report_submissions_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_report_submissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_report_submissions_id_seq OWNED BY public.ncs_hr_report_submissions.id;


--
-- Name: ncs_hr_report_template_fields; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_report_template_fields (
    id integer NOT NULL,
    template_id integer NOT NULL,
    field_label character varying(255) NOT NULL,
    field_type character varying(32) DEFAULT 'text'::character varying,
    field_options text,
    is_required smallint DEFAULT 0,
    sort_order integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_report_template_fields OWNER TO rise_user;

--
-- Name: ncs_hr_report_template_fields_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_report_template_fields_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_report_template_fields_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_report_template_fields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_report_template_fields_id_seq OWNED BY public.ncs_hr_report_template_fields.id;


--
-- Name: ncs_hr_report_templates; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_hr_report_templates (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    department character varying(64) DEFAULT 'ALL'::character varying,
    frequency character varying(32) DEFAULT 'monthly'::character varying,
    description text,
    is_active smallint DEFAULT 1,
    created_by integer,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now(),
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_hr_report_templates OWNER TO rise_user;

--
-- Name: ncs_hr_report_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_hr_report_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_hr_report_templates_id_seq OWNER TO rise_user;

--
-- Name: ncs_hr_report_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_hr_report_templates_id_seq OWNED BY public.ncs_hr_report_templates.id;


--
-- Name: ncs_ict_equipment; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_ict_equipment (
    id integer NOT NULL,
    item_code character varying(100) NOT NULL,
    item_name character varying(255) NOT NULL,
    category character varying(64) NOT NULL,
    brand_model character varying(150),
    serial_number character varying(100),
    specifications text,
    purchase_cost numeric(18,2) DEFAULT 0.00,
    purchase_date date,
    condition_rating character varying(30) DEFAULT 'GOOD'::character varying,
    status character varying(30) DEFAULT 'AVAILABLE'::character varying,
    location_assigned character varying(255) DEFAULT 'ICT Central Store'::character varying,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_ict_equipment OWNER TO rise_user;

--
-- Name: ncs_ict_equipment_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_ict_equipment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ict_equipment_id_seq OWNER TO rise_user;

--
-- Name: ncs_ict_equipment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_ict_equipment_id_seq OWNED BY public.ncs_ict_equipment.id;


--
-- Name: ncs_ict_expenses; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_ict_expenses (
    id integer NOT NULL,
    expense_ref character varying(100) NOT NULL,
    title character varying(255) NOT NULL,
    category character varying(64) NOT NULL,
    amount_ugx numeric(18,2) NOT NULL,
    expense_date date NOT NULL,
    vendor_supplier character varying(255),
    invoice_receipt_no character varying(100),
    approved_by character varying(255) DEFAULT 'IT Manager'::character varying,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_ict_expenses OWNER TO rise_user;

--
-- Name: ncs_ict_expenses_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_ict_expenses_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ict_expenses_id_seq OWNER TO rise_user;

--
-- Name: ncs_ict_expenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_ict_expenses_id_seq OWNED BY public.ncs_ict_expenses.id;


--
-- Name: ncs_ict_helpdesk_tickets; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_ict_helpdesk_tickets (
    id integer NOT NULL,
    ticket_number character varying(100) NOT NULL,
    requester_user_id integer,
    requester_name character varying(255) NOT NULL,
    department_name character varying(150) NOT NULL,
    category character varying(64) NOT NULL,
    priority character varying(30) DEFAULT 'MEDIUM'::character varying,
    subject character varying(255) NOT NULL,
    description text NOT NULL,
    assigned_to integer,
    assigned_to_name character varying(255),
    status character varying(30) DEFAULT 'OPEN'::character varying,
    resolution_notes text,
    closed_at timestamp without time zone,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_ict_helpdesk_tickets OWNER TO rise_user;

--
-- Name: ncs_ict_helpdesk_tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_ict_helpdesk_tickets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ict_helpdesk_tickets_id_seq OWNER TO rise_user;

--
-- Name: ncs_ict_helpdesk_tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_ict_helpdesk_tickets_id_seq OWNED BY public.ncs_ict_helpdesk_tickets.id;


--
-- Name: ncs_ict_issuances; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_ict_issuances (
    id integer NOT NULL,
    dispatch_ref character varying(100) NOT NULL,
    equipment_id integer NOT NULL,
    recipient_user_id integer,
    recipient_name character varying(255) NOT NULL,
    target_department_id integer,
    target_department_name character varying(150) NOT NULL,
    purpose character varying(255) NOT NULL,
    issue_date date NOT NULL,
    expected_return_date date,
    actual_return_date date,
    condition_on_issue character varying(50) DEFAULT 'GOOD'::character varying,
    condition_on_return character varying(50),
    issued_by_user_id integer,
    status character varying(30) DEFAULT 'CHECKED_OUT'::character varying,
    remarks text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_ict_issuances OWNER TO rise_user;

--
-- Name: ncs_ict_issuances_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_ict_issuances_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ict_issuances_id_seq OWNER TO rise_user;

--
-- Name: ncs_ict_issuances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_ict_issuances_id_seq OWNED BY public.ncs_ict_issuances.id;


--
-- Name: ncs_ict_maintenance_requisitions; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_ict_maintenance_requisitions (
    id integer NOT NULL,
    req_no character varying(100) NOT NULL,
    requester_user_id integer,
    requester_name character varying(255) NOT NULL,
    department_name character varying(150) NOT NULL,
    equipment_id integer,
    equipment_name character varying(255) NOT NULL,
    fault_category character varying(64) NOT NULL,
    fault_description text NOT NULL,
    priority character varying(30) DEFAULT 'MEDIUM'::character varying,
    service_type character varying(50) DEFAULT 'INTERNAL_IT'::character varying,
    vendor_name character varying(255),
    estimated_cost numeric(18,2) DEFAULT 0.00,
    resolution_notes text,
    status character varying(30) DEFAULT 'SUBMITTED'::character varying,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_ict_maintenance_requisitions OWNER TO rise_user;

--
-- Name: ncs_ict_maintenance_requisitions_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_ict_maintenance_requisitions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ict_maintenance_requisitions_id_seq OWNER TO rise_user;

--
-- Name: ncs_ict_maintenance_requisitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_ict_maintenance_requisitions_id_seq OWNED BY public.ncs_ict_maintenance_requisitions.id;


--
-- Name: ncs_invoice_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_invoice_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    invoice_id integer NOT NULL,
    item_id integer DEFAULT 0 NOT NULL,
    taxable smallint DEFAULT '1'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_invoice_items OWNER TO postgres;

--
-- Name: ncs_invoice_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_invoice_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_invoice_items_id_seq OWNER TO postgres;

--
-- Name: ncs_invoice_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_invoice_items_id_seq OWNED BY public.ncs_invoice_items.id;


--
-- Name: ncs_invoice_payments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_invoice_payments (
    id integer NOT NULL,
    amount double precision NOT NULL,
    payment_date date NOT NULL,
    payment_method_id integer NOT NULL,
    note text,
    invoice_id integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    transaction_id text,
    created_by integer DEFAULT 1,
    created_at timestamp without time zone
);


ALTER TABLE public.ncs_invoice_payments OWNER TO postgres;

--
-- Name: ncs_invoice_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_invoice_payments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_invoice_payments_id_seq OWNER TO postgres;

--
-- Name: ncs_invoice_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_invoice_payments_id_seq OWNED BY public.ncs_invoice_payments.id;


--
-- Name: ncs_invoices; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_invoices (
    id integer NOT NULL,
    type character varying(255) DEFAULT 'invoice'::character varying NOT NULL,
    client_id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    bill_date date NOT NULL,
    due_date date NOT NULL,
    note text,
    labels text,
    last_email_sent_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    tax_id3 integer DEFAULT 0 NOT NULL,
    recurring smallint DEFAULT '0'::smallint NOT NULL,
    recurring_invoice_id integer DEFAULT 0 NOT NULL,
    repeat_every integer DEFAULT 0 NOT NULL,
    repeat_type character varying(255) DEFAULT NULL::character varying,
    no_of_cycles integer DEFAULT 0 NOT NULL,
    next_recurring_date date,
    no_of_cycles_completed integer DEFAULT 0 NOT NULL,
    due_reminder_date date,
    recurring_reminder_date date,
    discount_amount double precision NOT NULL,
    discount_amount_type character varying(255) NOT NULL,
    discount_type character varying(255) NOT NULL,
    cancelled_at timestamp without time zone,
    cancelled_by integer NOT NULL,
    files text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    estimate_id integer DEFAULT 0 NOT NULL,
    main_invoice_id integer DEFAULT 0 NOT NULL,
    subscription_id integer DEFAULT 0 NOT NULL,
    invoice_total double precision NOT NULL,
    invoice_subtotal double precision NOT NULL,
    discount_total double precision NOT NULL,
    tax double precision NOT NULL,
    tax2 double precision NOT NULL,
    tax3 double precision NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    order_id integer DEFAULT 0 NOT NULL,
    display_id text NOT NULL,
    number_year integer,
    number_sequence integer,
    created_by integer NOT NULL
);


ALTER TABLE public.ncs_invoices OWNER TO postgres;

--
-- Name: ncs_invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_invoices_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_invoices_id_seq OWNER TO postgres;

--
-- Name: ncs_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_invoices_id_seq OWNED BY public.ncs_invoices.id;


--
-- Name: ncs_item_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_item_categories (
    id integer NOT NULL,
    title text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_item_categories OWNER TO postgres;

--
-- Name: ncs_item_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_item_categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_item_categories_id_seq OWNER TO postgres;

--
-- Name: ncs_item_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_item_categories_id_seq OWNED BY public.ncs_item_categories.id;


--
-- Name: ncs_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    files text NOT NULL,
    show_in_client_portal smallint DEFAULT '0'::smallint NOT NULL,
    category_id integer NOT NULL,
    taxable smallint DEFAULT '0'::smallint NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_items OWNER TO postgres;

--
-- Name: ncs_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_items_id_seq OWNER TO postgres;

--
-- Name: ncs_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_items_id_seq OWNED BY public.ncs_items.id;


--
-- Name: ncs_labels; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_labels (
    id integer NOT NULL,
    title text NOT NULL,
    color character varying(15) NOT NULL,
    context character varying(255) DEFAULT NULL::character varying,
    user_id integer DEFAULT 0 NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_labels OWNER TO postgres;

--
-- Name: ncs_labels_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_labels_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_labels_id_seq OWNER TO postgres;

--
-- Name: ncs_labels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_labels_id_seq OWNED BY public.ncs_labels.id;


--
-- Name: ncs_lead_source; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_lead_source (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    sort integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_lead_source OWNER TO postgres;

--
-- Name: ncs_lead_source_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_lead_source_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_lead_source_id_seq OWNER TO postgres;

--
-- Name: ncs_lead_source_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_lead_source_id_seq OWNED BY public.ncs_lead_source.id;


--
-- Name: ncs_lead_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_lead_status (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    color character varying(7) NOT NULL,
    sort integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_lead_status OWNER TO postgres;

--
-- Name: ncs_lead_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_lead_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_lead_status_id_seq OWNER TO postgres;

--
-- Name: ncs_lead_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_lead_status_id_seq OWNED BY public.ncs_lead_status.id;


--
-- Name: ncs_leave_applications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_leave_applications (
    id integer NOT NULL,
    leave_type_id integer NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    total_hours numeric(7,2) NOT NULL,
    total_days numeric(5,2) NOT NULL,
    applicant_id integer NOT NULL,
    reason text NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp without time zone NOT NULL,
    created_by integer NOT NULL,
    checked_at timestamp without time zone,
    checked_by integer DEFAULT 0 NOT NULL,
    files text NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_leave_applications OWNER TO postgres;

--
-- Name: ncs_leave_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_leave_applications_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_leave_applications_id_seq OWNER TO postgres;

--
-- Name: ncs_leave_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_leave_applications_id_seq OWNED BY public.ncs_leave_applications.id;


--
-- Name: ncs_leave_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_leave_types (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    color character varying(7) NOT NULL,
    description text,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_leave_types OWNER TO postgres;

--
-- Name: ncs_leave_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_leave_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_leave_types_id_seq OWNER TO postgres;

--
-- Name: ncs_leave_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_leave_types_id_seq OWNED BY public.ncs_leave_types.id;


--
-- Name: ncs_legal_contracts; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_legal_contracts (
    id integer NOT NULL,
    contract_reference character varying(64) NOT NULL,
    title character varying(255) NOT NULL,
    contract_type character varying(64) NOT NULL,
    first_party character varying(255) DEFAULT 'National Council of Sports'::character varying,
    second_party character varying(255) NOT NULL,
    contract_value_ugx numeric(15,2) DEFAULT 0.00,
    start_date date NOT NULL,
    expiry_date date NOT NULL,
    renewal_notice_days integer DEFAULT 60,
    legal_officer_name character varying(100) DEFAULT 'Senior Legal Counsel'::character varying,
    status character varying(32) DEFAULT 'ACTIVE'::character varying,
    document_summary text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_legal_contracts OWNER TO rise_user;

--
-- Name: ncs_legal_contracts_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_legal_contracts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_legal_contracts_id_seq OWNER TO rise_user;

--
-- Name: ncs_legal_contracts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_legal_contracts_id_seq OWNED BY public.ncs_legal_contracts.id;


--
-- Name: ncs_legal_disputes; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_legal_disputes (
    id integer NOT NULL,
    case_number character varying(64) NOT NULL,
    federation_name character varying(255) NOT NULL,
    complainant_name character varying(255) NOT NULL,
    respondent_name character varying(255) NOT NULL,
    subject_matter character varying(255) NOT NULL,
    dispute_category character varying(64) NOT NULL,
    filing_date date DEFAULT CURRENT_DATE,
    tribunal_chair_name character varying(128),
    case_status character varying(32) DEFAULT 'FILED'::character varying,
    ruling_summary text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_legal_disputes OWNER TO rise_user;

--
-- Name: ncs_legal_disputes_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_legal_disputes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_legal_disputes_id_seq OWNER TO rise_user;

--
-- Name: ncs_legal_disputes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_legal_disputes_id_seq OWNED BY public.ncs_legal_disputes.id;


--
-- Name: ncs_legal_litigation; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_legal_litigation (
    id integer NOT NULL,
    suit_number character varying(64) NOT NULL,
    plaintiff character varying(255) NOT NULL,
    defendant character varying(255) NOT NULL,
    court_level character varying(100) NOT NULL,
    legal_exposure_ugx numeric(15,2) DEFAULT 0.00,
    lead_counsel character varying(128) DEFAULT 'Solicitor General / NCS Legal'::character varying,
    case_status character varying(32) DEFAULT 'PENDING'::character varying,
    case_summary text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_legal_litigation OWNER TO rise_user;

--
-- Name: ncs_legal_litigation_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_legal_litigation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_legal_litigation_id_seq OWNER TO rise_user;

--
-- Name: ncs_legal_litigation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_legal_litigation_id_seq OWNED BY public.ncs_legal_litigation.id;


--
-- Name: ncs_legal_trademarks; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_legal_trademarks (
    id integer NOT NULL,
    trademark_code character varying(64) NOT NULL,
    mark_name character varying(255) NOT NULL,
    category character varying(64) NOT NULL,
    registration_number character varying(64),
    registration_date date DEFAULT CURRENT_DATE,
    expiry_date date,
    protection_status character varying(32) DEFAULT 'PROTECTED'::character varying,
    notes text,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_legal_trademarks OWNER TO rise_user;

--
-- Name: ncs_legal_trademarks_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_legal_trademarks_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_legal_trademarks_id_seq OWNER TO rise_user;

--
-- Name: ncs_legal_trademarks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_legal_trademarks_id_seq OWNED BY public.ncs_legal_trademarks.id;


--
-- Name: ncs_likes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_likes (
    id integer NOT NULL,
    project_comment_id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_likes OWNER TO postgres;

--
-- Name: ncs_likes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_likes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_likes_id_seq OWNER TO postgres;

--
-- Name: ncs_likes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_likes_id_seq OWNED BY public.ncs_likes.id;


--
-- Name: ncs_messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_messages (
    id integer NOT NULL,
    subject character varying(255) DEFAULT 'Untitled'::character varying NOT NULL,
    message text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    from_user_id integer NOT NULL,
    to_user_id integer NOT NULL,
    status character varying(255) DEFAULT 'unread'::character varying NOT NULL,
    message_id integer DEFAULT 0 NOT NULL,
    deleted integer DEFAULT 0 NOT NULL,
    files text NOT NULL,
    deleted_by_users text NOT NULL
);


ALTER TABLE public.ncs_messages OWNER TO postgres;

--
-- Name: ncs_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_messages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_messages_id_seq OWNER TO postgres;

--
-- Name: ncs_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_messages_id_seq OWNED BY public.ncs_messages.id;


--
-- Name: ncs_milestones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_milestones (
    id integer NOT NULL,
    title text NOT NULL,
    project_id integer NOT NULL,
    due_date date NOT NULL,
    description text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_milestones OWNER TO postgres;

--
-- Name: ncs_milestones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_milestones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_milestones_id_seq OWNER TO postgres;

--
-- Name: ncs_milestones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_milestones_id_seq OWNED BY public.ncs_milestones.id;


--
-- Name: ncs_note_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_note_category (
    id integer NOT NULL,
    name text,
    user_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_note_category OWNER TO postgres;

--
-- Name: ncs_note_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_note_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_note_category_id_seq OWNER TO postgres;

--
-- Name: ncs_note_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_note_category_id_seq OWNED BY public.ncs_note_category.id;


--
-- Name: ncs_notes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_notes (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    title text NOT NULL,
    description text,
    project_id integer DEFAULT 0 NOT NULL,
    client_id integer DEFAULT 0 NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    labels text,
    files text NOT NULL,
    is_public smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    category_id integer DEFAULT 0,
    color character varying(14) NOT NULL
);


ALTER TABLE public.ncs_notes OWNER TO postgres;

--
-- Name: ncs_notes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_notes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_notes_id_seq OWNER TO postgres;

--
-- Name: ncs_notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_notes_id_seq OWNED BY public.ncs_notes.id;


--
-- Name: ncs_notification_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_notification_settings (
    id integer NOT NULL,
    event character varying(250) NOT NULL,
    category character varying(50) NOT NULL,
    enable_email integer DEFAULT 0 NOT NULL,
    enable_web integer DEFAULT 0 NOT NULL,
    enable_slack integer DEFAULT 0 NOT NULL,
    notify_to_team text NOT NULL,
    notify_to_team_members text NOT NULL,
    notify_to_terms text NOT NULL,
    sort integer NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_notification_settings OWNER TO postgres;

--
-- Name: ncs_notification_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_notification_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_notification_settings_id_seq OWNER TO postgres;

--
-- Name: ncs_notification_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_notification_settings_id_seq OWNED BY public.ncs_notification_settings.id;


--
-- Name: ncs_notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_notifications (
    id integer NOT NULL,
    user_id integer NOT NULL,
    description text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    notify_to text NOT NULL,
    read_by text NOT NULL,
    event character varying(250) NOT NULL,
    project_id integer NOT NULL,
    task_id integer NOT NULL,
    project_comment_id integer NOT NULL,
    ticket_id integer NOT NULL,
    ticket_comment_id integer NOT NULL,
    project_file_id integer NOT NULL,
    leave_id integer NOT NULL,
    post_id integer NOT NULL,
    to_user_id integer NOT NULL,
    activity_log_id integer NOT NULL,
    client_id integer NOT NULL,
    lead_id integer NOT NULL,
    invoice_payment_id integer NOT NULL,
    invoice_id integer NOT NULL,
    estimate_id integer NOT NULL,
    contract_id integer NOT NULL,
    order_id integer NOT NULL,
    estimate_request_id integer NOT NULL,
    actual_message_id integer NOT NULL,
    parent_message_id integer NOT NULL,
    event_id integer NOT NULL,
    announcement_id integer NOT NULL,
    proposal_id integer NOT NULL,
    estimate_comment_id integer NOT NULL,
    subscription_id integer NOT NULL,
    expense_id integer NOT NULL,
    proposal_comment_id integer NOT NULL,
    reminder_log_id integer NOT NULL,
    deleted integer DEFAULT 0 NOT NULL,
    reminder_id integer DEFAULT 0
);


ALTER TABLE public.ncs_notifications OWNER TO postgres;

--
-- Name: ncs_notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_notifications_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_notifications_id_seq OWNER TO postgres;

--
-- Name: ncs_notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_notifications_id_seq OWNED BY public.ncs_notifications.id;


--
-- Name: ncs_order_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_order_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    order_id integer NOT NULL,
    created_by integer NOT NULL,
    item_id integer DEFAULT 0 NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    created_by_hash text NOT NULL
);


ALTER TABLE public.ncs_order_items OWNER TO postgres;

--
-- Name: ncs_order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_order_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_order_items_id_seq OWNER TO postgres;

--
-- Name: ncs_order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_order_items_id_seq OWNED BY public.ncs_order_items.id;


--
-- Name: ncs_order_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_order_status (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    color character varying(7) NOT NULL,
    sort integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_order_status OWNER TO postgres;

--
-- Name: ncs_order_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_order_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_order_status_id_seq OWNER TO postgres;

--
-- Name: ncs_order_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_order_status_id_seq OWNED BY public.ncs_order_status.id;


--
-- Name: ncs_orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_orders (
    id integer NOT NULL,
    client_id integer NOT NULL,
    order_date date NOT NULL,
    note text,
    status_id integer NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    discount_amount double precision NOT NULL,
    discount_amount_type character varying(255) NOT NULL,
    discount_type character varying(255) NOT NULL,
    created_by integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    files text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    created_by_hash text NOT NULL
);


ALTER TABLE public.ncs_orders OWNER TO postgres;

--
-- Name: ncs_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_orders_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_orders_id_seq OWNER TO postgres;

--
-- Name: ncs_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_orders_id_seq OWNED BY public.ncs_orders.id;


--
-- Name: ncs_pages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_pages (
    id integer NOT NULL,
    title text,
    content text,
    slug text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    internal_use_only smallint DEFAULT '0'::smallint NOT NULL,
    visible_to_team_members_only smallint DEFAULT '0'::smallint NOT NULL,
    visible_to_clients_only smallint DEFAULT '0'::smallint NOT NULL,
    full_width smallint DEFAULT '0'::smallint NOT NULL,
    hide_topbar smallint DEFAULT '0'::smallint NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_pages OWNER TO postgres;

--
-- Name: ncs_pages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_pages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_pages_id_seq OWNER TO postgres;

--
-- Name: ncs_pages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_pages_id_seq OWNED BY public.ncs_pages.id;


--
-- Name: ncs_payment_methods; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_payment_methods (
    id integer NOT NULL,
    title text NOT NULL,
    type character varying(100) DEFAULT 'custom'::character varying NOT NULL,
    description text NOT NULL,
    online_payable smallint DEFAULT '0'::smallint NOT NULL,
    available_on_invoice smallint DEFAULT '0'::smallint NOT NULL,
    minimum_payment_amount double precision DEFAULT '0'::double precision NOT NULL,
    settings text NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_payment_methods OWNER TO postgres;

--
-- Name: ncs_payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_payment_methods_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_payment_methods_id_seq OWNER TO postgres;

--
-- Name: ncs_payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_payment_methods_id_seq OWNED BY public.ncs_payment_methods.id;


--
-- Name: ncs_paypal_ipn; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_paypal_ipn (
    id integer NOT NULL,
    verification_code text NOT NULL,
    payment_verification_code text NOT NULL,
    invoice_id integer NOT NULL,
    contact_user_id integer NOT NULL,
    client_id integer NOT NULL,
    payment_method_id integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_paypal_ipn OWNER TO postgres;

--
-- Name: ncs_paypal_ipn_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_paypal_ipn_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_paypal_ipn_id_seq OWNER TO postgres;

--
-- Name: ncs_paypal_ipn_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_paypal_ipn_id_seq OWNED BY public.ncs_paypal_ipn.id;


--
-- Name: ncs_pin_comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_pin_comments (
    id integer NOT NULL,
    project_comment_id integer DEFAULT 0 NOT NULL,
    ticket_comment_id integer DEFAULT 0 NOT NULL,
    pinned_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_pin_comments OWNER TO postgres;

--
-- Name: ncs_pin_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_pin_comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_pin_comments_id_seq OWNER TO postgres;

--
-- Name: ncs_pin_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_pin_comments_id_seq OWNED BY public.ncs_pin_comments.id;


--
-- Name: ncs_posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_posts (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    description text NOT NULL,
    post_id integer NOT NULL,
    share_with text,
    files text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_posts OWNER TO postgres;

--
-- Name: ncs_posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_posts_id_seq OWNER TO postgres;

--
-- Name: ncs_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_posts_id_seq OWNED BY public.ncs_posts.id;


--
-- Name: ncs_procurement_form5; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_procurement_form5 (
    id integer NOT NULL,
    procurement_ref_no character varying(100) NOT NULL,
    pde_code character varying(50) DEFAULT 'NCS'::character varying,
    procurement_type character varying(50) NOT NULL,
    financial_year character varying(20) NOT NULL,
    sequence_number integer NOT NULL,
    budget_category character varying(50) NOT NULL,
    recurrent_budget_code character varying(50),
    development_budget_code character varying(50),
    project_code character varying(50),
    project_title character varying(255),
    is_multiyear boolean DEFAULT false,
    required_ugx_yr1 numeric(18,2) DEFAULT 0.00 NOT NULL,
    required_ugx_yr2 numeric(18,2) DEFAULT 0.00,
    required_ugx_yr3 numeric(18,2) DEFAULT 0.00,
    required_ugx_yr4 numeric(18,2) DEFAULT 0.00,
    subject_of_procurement text NOT NULL,
    procurement_plan_ref character varying(100) NOT NULL,
    location_for_delivery character varying(255) NOT NULL,
    date_required date NOT NULL,
    currency character varying(10) DEFAULT 'UGX'::character varying,
    grand_total_estimated_cost numeric(18,2) DEFAULT 0.00 NOT NULL,
    status character varying(50) DEFAULT 'SUBMITTED'::character varying,
    requester_user_id integer NOT NULL,
    requested_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    hod_user_id integer,
    hod_approval_status character varying(30) DEFAULT 'PENDING'::character varying,
    hod_comments text,
    hod_decided_at timestamp without time zone,
    vote_head_no character varying(50),
    programme character varying(100),
    sub_programme character varying(100),
    funding_status character varying(30) DEFAULT 'PENDING'::character varying,
    accounting_officer_user_id integer,
    accounting_officer_status character varying(30) DEFAULT 'PENDING'::character varying,
    accounting_officer_comments text,
    accounting_officer_decided_at timestamp without time zone,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    department_id integer DEFAULT 0,
    requester_rank integer DEFAULT 3,
    assigned_approver_id integer DEFAULT 0,
    target_department_id integer DEFAULT 0,
    workflow_history text
);


ALTER TABLE public.ncs_procurement_form5 OWNER TO rise_user;

--
-- Name: ncs_procurement_form5_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_procurement_form5_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_procurement_form5_id_seq OWNER TO rise_user;

--
-- Name: ncs_procurement_form5_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_procurement_form5_id_seq OWNED BY public.ncs_procurement_form5.id;


--
-- Name: ncs_procurement_form5_items; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_procurement_form5_items (
    id integer NOT NULL,
    form5_id integer,
    item_no integer NOT NULL,
    description text NOT NULL,
    quantity numeric(12,2) NOT NULL,
    unit_of_measure character varying(50) NOT NULL,
    estimated_unit_cost numeric(15,2) NOT NULL,
    market_price numeric(15,2) NOT NULL,
    line_total_cost numeric(15,2) NOT NULL,
    deleted integer DEFAULT 0
);


ALTER TABLE public.ncs_procurement_form5_items OWNER TO rise_user;

--
-- Name: ncs_procurement_form5_items_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_procurement_form5_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_procurement_form5_items_id_seq OWNER TO rise_user;

--
-- Name: ncs_procurement_form5_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_procurement_form5_items_id_seq OWNED BY public.ncs_procurement_form5_items.id;


--
-- Name: ncs_procurement_plans; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_procurement_plans (
    id integer NOT NULL,
    financial_year character varying(16) NOT NULL,
    procurement_ref_no character varying(64) NOT NULL,
    subject_of_procurement character varying(255) NOT NULL,
    procurement_type character varying(32) NOT NULL,
    procurement_method character varying(64) NOT NULL,
    estimated_cost numeric(18,2) NOT NULL,
    user_department character varying(64) NOT NULL,
    planned_invitation_date date,
    planned_contract_signature_date date,
    status character varying(32) DEFAULT 'PLANNED'::character varying,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_procurement_plans OWNER TO rise_user;

--
-- Name: ncs_procurement_plans_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_procurement_plans_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_procurement_plans_id_seq OWNER TO rise_user;

--
-- Name: ncs_procurement_plans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_procurement_plans_id_seq OWNED BY public.ncs_procurement_plans.id;


--
-- Name: ncs_procurement_suppliers; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_procurement_suppliers (
    id integer NOT NULL,
    company_name character varying(255) NOT NULL,
    ppda_registration_no character varying(64) NOT NULL,
    tin_number character varying(32) NOT NULL,
    contact_person character varying(128) NOT NULL,
    contact_email character varying(128) NOT NULL,
    contact_phone character varying(32) NOT NULL,
    is_blacklisted boolean DEFAULT false,
    blacklist_reason text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_procurement_suppliers OWNER TO rise_user;

--
-- Name: ncs_procurement_suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_procurement_suppliers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_procurement_suppliers_id_seq OWNER TO rise_user;

--
-- Name: ncs_procurement_suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_procurement_suppliers_id_seq OWNED BY public.ncs_procurement_suppliers.id;


--
-- Name: ncs_project_comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_comments (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    description text NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    comment_id integer DEFAULT 0 NOT NULL,
    task_id integer DEFAULT 0 NOT NULL,
    file_id integer DEFAULT 0 NOT NULL,
    customer_feedback_id integer DEFAULT 0 NOT NULL,
    files text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_project_comments OWNER TO postgres;

--
-- Name: ncs_project_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_project_comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_project_comments_id_seq OWNER TO postgres;

--
-- Name: ncs_project_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_project_comments_id_seq OWNED BY public.ncs_project_comments.id;


--
-- Name: ncs_project_facility_relations; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_project_facility_relations (
    project_id integer NOT NULL,
    facility_id integer NOT NULL
);


ALTER TABLE public.ncs_project_facility_relations OWNER TO rise_user;

--
-- Name: ncs_project_files; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_files (
    id integer NOT NULL,
    file_name text NOT NULL,
    file_id text,
    service_type character varying(20) DEFAULT NULL::character varying,
    description text,
    file_size double precision NOT NULL,
    created_at timestamp without time zone NOT NULL,
    project_id integer NOT NULL,
    uploaded_by integer NOT NULL,
    category_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    folder_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_project_files OWNER TO postgres;

--
-- Name: ncs_project_files_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_project_files_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_project_files_id_seq OWNER TO postgres;

--
-- Name: ncs_project_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_project_files_id_seq OWNED BY public.ncs_project_files.id;


--
-- Name: ncs_project_members; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_members (
    id integer NOT NULL,
    user_id integer NOT NULL,
    project_id integer NOT NULL,
    is_leader smallint DEFAULT '0'::smallint,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_project_members OWNER TO postgres;

--
-- Name: ncs_project_members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_project_members_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_project_members_id_seq OWNER TO postgres;

--
-- Name: ncs_project_members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_project_members_id_seq OWNED BY public.ncs_project_members.id;


--
-- Name: ncs_project_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_settings (
    project_id integer NOT NULL,
    setting_name character varying(100) NOT NULL,
    setting_value text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_project_settings OWNER TO postgres;

--
-- Name: ncs_project_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_status (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    title_language_key text NOT NULL,
    key_name character varying(100) NOT NULL,
    icon character varying(50) NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_project_status OWNER TO postgres;

--
-- Name: ncs_project_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_project_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_project_status_id_seq OWNER TO postgres;

--
-- Name: ncs_project_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_project_status_id_seq OWNED BY public.ncs_project_status.id;


--
-- Name: ncs_project_time; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_project_time (
    id integer NOT NULL,
    project_id integer NOT NULL,
    user_id integer NOT NULL,
    start_time timestamp without time zone NOT NULL,
    end_time timestamp without time zone,
    hours double precision NOT NULL,
    status character varying(255) DEFAULT 'logged'::character varying NOT NULL,
    note text,
    task_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_project_time OWNER TO postgres;

--
-- Name: ncs_project_time_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_project_time_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_project_time_id_seq OWNER TO postgres;

--
-- Name: ncs_project_time_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_project_time_id_seq OWNED BY public.ncs_project_time.id;


--
-- Name: ncs_projects; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_projects (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    project_type character varying(255) DEFAULT 'client_project'::character varying NOT NULL,
    start_date date,
    deadline date,
    client_id integer NOT NULL,
    created_date date,
    created_by integer DEFAULT 0 NOT NULL,
    status character varying(255) DEFAULT 'open'::character varying NOT NULL,
    status_id integer DEFAULT 1 NOT NULL,
    labels text,
    price double precision DEFAULT '0'::double precision NOT NULL,
    starred_by text NOT NULL,
    estimate_id integer NOT NULL,
    order_id integer NOT NULL,
    proposal_id integer DEFAULT 0,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_projects OWNER TO postgres;

--
-- Name: ncs_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_projects_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_projects_id_seq OWNER TO postgres;

--
-- Name: ncs_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_projects_id_seq OWNED BY public.ncs_projects.id;


--
-- Name: ncs_proposal_comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_proposal_comments (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    description text NOT NULL,
    proposal_id integer DEFAULT 0 NOT NULL,
    files text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_proposal_comments OWNER TO postgres;

--
-- Name: ncs_proposal_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_proposal_comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_proposal_comments_id_seq OWNER TO postgres;

--
-- Name: ncs_proposal_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_proposal_comments_id_seq OWNED BY public.ncs_proposal_comments.id;


--
-- Name: ncs_proposal_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_proposal_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    proposal_id integer NOT NULL,
    item_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_proposal_items OWNER TO postgres;

--
-- Name: ncs_proposal_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_proposal_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_proposal_items_id_seq OWNER TO postgres;

--
-- Name: ncs_proposal_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_proposal_items_id_seq OWNED BY public.ncs_proposal_items.id;


--
-- Name: ncs_proposal_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_proposal_templates (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    template text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_proposal_templates OWNER TO postgres;

--
-- Name: ncs_proposal_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_proposal_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_proposal_templates_id_seq OWNER TO postgres;

--
-- Name: ncs_proposal_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_proposal_templates_id_seq OWNED BY public.ncs_proposal_templates.id;


--
-- Name: ncs_proposals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_proposals (
    id integer NOT NULL,
    client_id integer NOT NULL,
    proposal_date date NOT NULL,
    valid_until date NOT NULL,
    note text,
    last_email_sent_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    discount_type character varying(255) NOT NULL,
    discount_amount double precision NOT NULL,
    discount_amount_type character varying(255) NOT NULL,
    content text NOT NULL,
    public_key character varying(10) NOT NULL,
    accepted_by integer DEFAULT 0 NOT NULL,
    created_by integer DEFAULT 0 NOT NULL,
    total_views integer DEFAULT 0 NOT NULL,
    last_preview_seen timestamp without time zone,
    meta_data text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_proposals OWNER TO postgres;

--
-- Name: ncs_proposals_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_proposals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_proposals_id_seq OWNER TO postgres;

--
-- Name: ncs_proposals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_proposals_id_seq OWNED BY public.ncs_proposals.id;


--
-- Name: ncs_reminder_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_reminder_logs (
    id integer NOT NULL,
    context character varying(255) NOT NULL,
    context_id integer NOT NULL,
    reminder_event character varying(255) DEFAULT NULL::character varying,
    notification_status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    reminder_date date,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    reminder_time time without time zone,
    notify_to text NOT NULL
);


ALTER TABLE public.ncs_reminder_logs OWNER TO postgres;

--
-- Name: ncs_reminder_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_reminder_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_reminder_logs_id_seq OWNER TO postgres;

--
-- Name: ncs_reminder_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_reminder_logs_id_seq OWNED BY public.ncs_reminder_logs.id;


--
-- Name: ncs_reminder_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_reminder_settings (
    id integer NOT NULL,
    type character varying(20) DEFAULT 'app'::character varying NOT NULL,
    context text NOT NULL,
    reminder_event text NOT NULL,
    reminder1 character varying(255) DEFAULT NULL::character varying,
    reminder2 integer,
    reminder3 integer,
    reminder4 integer,
    reminder5 integer,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    user_id integer DEFAULT 0
);


ALTER TABLE public.ncs_reminder_settings OWNER TO postgres;

--
-- Name: ncs_reminder_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_reminder_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_reminder_settings_id_seq OWNER TO postgres;

--
-- Name: ncs_reminder_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_reminder_settings_id_seq OWNED BY public.ncs_reminder_settings.id;


--
-- Name: ncs_roles; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_roles (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    department_id integer DEFAULT 0,
    rank integer DEFAULT 1,
    permissions text,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_roles OWNER TO rise_user;

--
-- Name: ncs_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_roles_id_seq OWNER TO rise_user;

--
-- Name: ncs_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_roles_id_seq OWNED BY public.ncs_roles.id;


--
-- Name: ncs_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_settings (
    setting_name character varying(100) NOT NULL,
    setting_value text NOT NULL,
    type character varying(20) DEFAULT 'app'::character varying NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_settings OWNER TO postgres;

--
-- Name: ncs_social_links; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_social_links (
    id integer NOT NULL,
    user_id integer NOT NULL,
    facebook text,
    twitter text,
    linkedin text,
    googleplus text,
    digg text,
    youtube text,
    pinterest text,
    instagram text,
    github text,
    tumblr text,
    vine text,
    whatsapp text,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_social_links OWNER TO postgres;

--
-- Name: ncs_store_audit_trail; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_audit_trail (
    id integer NOT NULL,
    item_id integer NOT NULL,
    action_type character varying(64) NOT NULL,
    quantity integer DEFAULT 0,
    from_location character varying(100) DEFAULT NULL::character varying,
    to_location character varying(100) DEFAULT NULL::character varying,
    reported_by integer NOT NULL,
    details text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_store_audit_trail OWNER TO rise_user;

--
-- Name: ncs_store_audit_trail_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_audit_trail_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_audit_trail_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_audit_trail_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_audit_trail_id_seq OWNED BY public.ncs_store_audit_trail.id;


--
-- Name: ncs_store_grn; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_grn (
    id integer NOT NULL,
    grn_number character varying(64) NOT NULL,
    po_reference character varying(64) DEFAULT NULL::character varying,
    supplier_name character varying(255) NOT NULL,
    received_by integer NOT NULL,
    received_date date NOT NULL,
    items_summary text,
    total_value numeric(18,2) DEFAULT 0.00,
    quality_status character varying(32) DEFAULT 'accepted'::character varying,
    delivery_note_ref character varying(100) DEFAULT NULL::character varying,
    notes text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_store_grn OWNER TO rise_user;

--
-- Name: ncs_store_grn_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_grn_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_grn_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_grn_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_grn_id_seq OWNED BY public.ncs_store_grn.id;


--
-- Name: ncs_store_inventory_items; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_inventory_items (
    id integer NOT NULL,
    sku_code character varying(64) NOT NULL,
    item_name character varying(255) NOT NULL,
    category character varying(64) NOT NULL,
    unit_of_measure character varying(32) DEFAULT 'Units'::character varying NOT NULL,
    unit_cost numeric(18,2) DEFAULT 0.00 NOT NULL,
    quantity_on_hand integer DEFAULT 0 NOT NULL,
    min_reorder_level integer DEFAULT 5 NOT NULL,
    warehouse_bin_location character varying(64) DEFAULT 'Warehouse A'::character varying,
    condition character varying(32) DEFAULT 'good'::character varying,
    department_id integer DEFAULT 0,
    assigned_role_id integer DEFAULT 0,
    assigned_user_id integer DEFAULT 0,
    status character varying(32) DEFAULT 'in_stock'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    created_by integer DEFAULT 0,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_store_inventory_items OWNER TO rise_user;

--
-- Name: ncs_store_inventory_items_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_inventory_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_inventory_items_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_inventory_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_inventory_items_id_seq OWNED BY public.ncs_store_inventory_items.id;


--
-- Name: ncs_store_issuances; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_issuances (
    id integer NOT NULL,
    siv_number character varying(64) NOT NULL,
    requisition_id integer DEFAULT 0,
    item_id integer NOT NULL,
    quantity_issued integer DEFAULT 1 NOT NULL,
    issued_to_department_id integer DEFAULT 0,
    issued_to_role_id integer DEFAULT 0,
    issued_to_user_id integer DEFAULT 0,
    condition_on_issue character varying(64) DEFAULT 'good'::character varying,
    issued_by integer NOT NULL,
    issued_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    handover_notes text,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_store_issuances OWNER TO rise_user;

--
-- Name: ncs_store_issuances_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_issuances_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_issuances_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_issuances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_issuances_id_seq OWNED BY public.ncs_store_issuances.id;


--
-- Name: ncs_store_requisitions; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_requisitions (
    id integer NOT NULL,
    req_number character varying(64) NOT NULL,
    requested_by integer NOT NULL,
    department_id integer NOT NULL,
    target_office character varying(100) DEFAULT NULL::character varying,
    reason text NOT NULL,
    status character varying(32) DEFAULT 'pending_hod'::character varying,
    hod_approved_by integer DEFAULT 0,
    hod_remarks text,
    issued_by integer DEFAULT 0,
    requested_date date NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    deleted smallint DEFAULT 0
);


ALTER TABLE public.ncs_store_requisitions OWNER TO rise_user;

--
-- Name: ncs_store_requisitions_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_requisitions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_requisitions_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_requisitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_requisitions_id_seq OWNED BY public.ncs_store_requisitions.id;


--
-- Name: ncs_store_stock_takes; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_store_stock_takes (
    id integer NOT NULL,
    take_date date NOT NULL,
    conducted_by integer NOT NULL,
    item_id integer NOT NULL,
    book_qty integer DEFAULT 0 NOT NULL,
    physical_qty integer DEFAULT 0 NOT NULL,
    variance integer DEFAULT 0 NOT NULL,
    reconciliation_notes text,
    status character varying(32) DEFAULT 'completed'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_store_stock_takes OWNER TO rise_user;

--
-- Name: ncs_store_stock_takes_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_store_stock_takes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_store_stock_takes_id_seq OWNER TO rise_user;

--
-- Name: ncs_store_stock_takes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_store_stock_takes_id_seq OWNED BY public.ncs_store_stock_takes.id;


--
-- Name: ncs_stripe_ipn; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_stripe_ipn (
    id integer NOT NULL,
    session_id text NOT NULL,
    verification_code text NOT NULL,
    payment_verification_code text NOT NULL,
    invoice_id integer NOT NULL,
    contact_user_id integer NOT NULL,
    client_id integer NOT NULL,
    payment_method_id integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    setup_intent text NOT NULL,
    subscription_id integer NOT NULL
);


ALTER TABLE public.ncs_stripe_ipn OWNER TO postgres;

--
-- Name: ncs_stripe_ipn_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_stripe_ipn_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_stripe_ipn_id_seq OWNER TO postgres;

--
-- Name: ncs_stripe_ipn_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_stripe_ipn_id_seq OWNED BY public.ncs_stripe_ipn.id;


--
-- Name: ncs_subscription_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_subscription_items (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    quantity double precision NOT NULL,
    unit_type character varying(20) DEFAULT ''::character varying NOT NULL,
    rate double precision NOT NULL,
    total double precision NOT NULL,
    sort integer DEFAULT 0 NOT NULL,
    subscription_id integer NOT NULL,
    item_id integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_subscription_items OWNER TO postgres;

--
-- Name: ncs_subscription_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_subscription_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_subscription_items_id_seq OWNER TO postgres;

--
-- Name: ncs_subscription_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_subscription_items_id_seq OWNED BY public.ncs_subscription_items.id;


--
-- Name: ncs_subscriptions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_subscriptions (
    id integer NOT NULL,
    title text NOT NULL,
    client_id integer NOT NULL,
    bill_date date,
    end_date date,
    note text,
    labels text NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    payment_status character varying(255) DEFAULT 'success'::character varying NOT NULL,
    tax_id integer DEFAULT 0 NOT NULL,
    tax_id2 integer DEFAULT 0 NOT NULL,
    repeat_every integer DEFAULT 1 NOT NULL,
    repeat_type character varying(255) DEFAULT NULL::character varying,
    no_of_cycles integer DEFAULT 0 NOT NULL,
    next_recurring_date date,
    no_of_cycles_completed integer DEFAULT 0 NOT NULL,
    cancelled_at timestamp without time zone,
    cancelled_by integer NOT NULL,
    files text NOT NULL,
    company_id integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    type character varying(255) DEFAULT 'app'::character varying NOT NULL,
    stripe_subscription_id text NOT NULL,
    stripe_product_id text NOT NULL,
    stripe_product_price_id text NOT NULL
);


ALTER TABLE public.ncs_subscriptions OWNER TO postgres;

--
-- Name: ncs_subscriptions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_subscriptions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_subscriptions_id_seq OWNER TO postgres;

--
-- Name: ncs_subscriptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_subscriptions_id_seq OWNED BY public.ncs_subscriptions.id;


--
-- Name: ncs_supplier_contacts; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_supplier_contacts (
    id integer NOT NULL,
    supplier_id integer NOT NULL,
    first_name character varying(64) NOT NULL,
    last_name character varying(64) NOT NULL,
    job_title character varying(128),
    email character varying(128) NOT NULL,
    phone character varying(32),
    alternative_phone character varying(32),
    is_primary_contact boolean DEFAULT false,
    notes text,
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_supplier_contacts OWNER TO rise_user;

--
-- Name: ncs_supplier_contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_supplier_contacts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_supplier_contacts_id_seq OWNER TO rise_user;

--
-- Name: ncs_supplier_contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_supplier_contacts_id_seq OWNED BY public.ncs_supplier_contacts.id;


--
-- Name: ncs_suppliers; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_suppliers (
    id integer NOT NULL,
    supplier_code character varying(64),
    company_name character varying(255) NOT NULL,
    category character varying(128) DEFAULT 'General Supplies'::character varying,
    ppda_registration_no character varying(64),
    tin_number character varying(32),
    vat_number character varying(32),
    address text,
    city character varying(64) DEFAULT 'Kampala'::character varying,
    country character varying(64) DEFAULT 'Uganda'::character varying,
    website character varying(255),
    phone character varying(64),
    email character varying(128),
    prequalification_status character varying(32) DEFAULT 'PRE_QUALIFIED'::character varying,
    is_blacklisted boolean DEFAULT false,
    blacklist_reason text,
    rating numeric(3,2) DEFAULT 4.50,
    payment_terms character varying(64) DEFAULT 'Net 30 Days'::character varying,
    bank_name character varying(128),
    bank_account_no character varying(64),
    bank_branch character varying(128),
    deleted integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_suppliers OWNER TO rise_user;

--
-- Name: ncs_suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_suppliers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_suppliers_id_seq OWNER TO rise_user;

--
-- Name: ncs_suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_suppliers_id_seq OWNED BY public.ncs_suppliers.id;


--
-- Name: ncs_task_priority; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_task_priority (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    icon character varying(20) NOT NULL,
    color character varying(7) NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_task_priority OWNER TO postgres;

--
-- Name: ncs_task_priority_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_task_priority_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_task_priority_id_seq OWNER TO postgres;

--
-- Name: ncs_task_priority_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_task_priority_id_seq OWNED BY public.ncs_task_priority.id;


--
-- Name: ncs_task_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_task_status (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    key_name character varying(100) NOT NULL,
    color character varying(7) NOT NULL,
    sort integer NOT NULL,
    hide_from_kanban smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    hide_from_non_project_related_tasks smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_task_status OWNER TO postgres;

--
-- Name: ncs_task_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_task_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_task_status_id_seq OWNER TO postgres;

--
-- Name: ncs_task_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_task_status_id_seq OWNED BY public.ncs_task_status.id;


--
-- Name: ncs_tasks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_tasks (
    id integer NOT NULL,
    title text NOT NULL,
    description text,
    project_id integer NOT NULL,
    milestone_id integer DEFAULT 0 NOT NULL,
    assigned_to integer NOT NULL,
    deadline timestamp without time zone,
    labels text,
    points smallint DEFAULT '1'::smallint NOT NULL,
    status character varying(255) DEFAULT 'to_do'::character varying NOT NULL,
    status_id integer NOT NULL,
    priority_id integer NOT NULL,
    start_date timestamp without time zone,
    collaborators text NOT NULL,
    sort double precision DEFAULT '0'::double precision NOT NULL,
    recurring smallint DEFAULT '0'::smallint NOT NULL,
    repeat_every integer DEFAULT 0 NOT NULL,
    repeat_type character varying(255) DEFAULT NULL::character varying,
    no_of_cycles integer DEFAULT 0 NOT NULL,
    recurring_task_id integer DEFAULT 0 NOT NULL,
    no_of_cycles_completed integer DEFAULT 0 NOT NULL,
    created_date date,
    blocking text NOT NULL,
    blocked_by text NOT NULL,
    parent_task_id integer NOT NULL,
    next_recurring_date date,
    reminder_date date,
    ticket_id integer NOT NULL,
    status_changed_at timestamp without time zone,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    expense_id integer DEFAULT 0 NOT NULL,
    subscription_id integer DEFAULT 0 NOT NULL,
    proposal_id integer DEFAULT 0 NOT NULL,
    contract_id integer DEFAULT 0 NOT NULL,
    order_id integer DEFAULT 0 NOT NULL,
    estimate_id integer DEFAULT 0 NOT NULL,
    invoice_id integer DEFAULT 0 NOT NULL,
    lead_id integer DEFAULT 0 NOT NULL,
    client_id integer DEFAULT 0 NOT NULL,
    context character varying(255) DEFAULT 'general'::character varying NOT NULL,
    created_by integer
);


ALTER TABLE public.ncs_tasks OWNER TO postgres;

--
-- Name: ncs_tasks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_tasks_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_tasks_id_seq OWNER TO postgres;

--
-- Name: ncs_tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_tasks_id_seq OWNED BY public.ncs_tasks.id;


--
-- Name: ncs_taxes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_taxes (
    id integer NOT NULL,
    title text NOT NULL,
    percentage double precision NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    stripe_tax_id text NOT NULL
);


ALTER TABLE public.ncs_taxes OWNER TO postgres;

--
-- Name: ncs_taxes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_taxes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_taxes_id_seq OWNER TO postgres;

--
-- Name: ncs_taxes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_taxes_id_seq OWNED BY public.ncs_taxes.id;


--
-- Name: ncs_team; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_team (
    id integer NOT NULL,
    title text NOT NULL,
    members text NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_team OWNER TO postgres;

--
-- Name: ncs_team_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_team_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_team_id_seq OWNER TO postgres;

--
-- Name: ncs_team_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_team_id_seq OWNED BY public.ncs_team.id;


--
-- Name: ncs_team_member_job_info; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_team_member_job_info (
    id integer NOT NULL,
    user_id integer NOT NULL,
    date_of_hire date,
    deleted integer DEFAULT 0 NOT NULL,
    salary double precision DEFAULT '0'::double precision NOT NULL,
    salary_term character varying(20) DEFAULT NULL::character varying
);


ALTER TABLE public.ncs_team_member_job_info OWNER TO postgres;

--
-- Name: ncs_team_member_job_info_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_team_member_job_info_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_team_member_job_info_id_seq OWNER TO postgres;

--
-- Name: ncs_team_member_job_info_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_team_member_job_info_id_seq OWNED BY public.ncs_team_member_job_info.id;


--
-- Name: ncs_ticket_comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_ticket_comments (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    description text NOT NULL,
    ticket_id integer NOT NULL,
    files text,
    is_note smallint DEFAULT '0'::smallint NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_ticket_comments OWNER TO postgres;

--
-- Name: ncs_ticket_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_ticket_comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ticket_comments_id_seq OWNER TO postgres;

--
-- Name: ncs_ticket_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_ticket_comments_id_seq OWNED BY public.ncs_ticket_comments.id;


--
-- Name: ncs_ticket_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_ticket_templates (
    id integer NOT NULL,
    title text NOT NULL,
    description text NOT NULL,
    ticket_type_id integer NOT NULL,
    private text NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_ticket_templates OWNER TO postgres;

--
-- Name: ncs_ticket_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_ticket_templates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ticket_templates_id_seq OWNER TO postgres;

--
-- Name: ncs_ticket_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_ticket_templates_id_seq OWNED BY public.ncs_ticket_templates.id;


--
-- Name: ncs_ticket_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_ticket_types (
    id integer NOT NULL,
    title text NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.ncs_ticket_types OWNER TO postgres;

--
-- Name: ncs_ticket_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_ticket_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_ticket_types_id_seq OWNER TO postgres;

--
-- Name: ncs_ticket_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_ticket_types_id_seq OWNED BY public.ncs_ticket_types.id;


--
-- Name: ncs_tickets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_tickets (
    id integer NOT NULL,
    client_id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    ticket_type_id integer NOT NULL,
    title text NOT NULL,
    created_by integer NOT NULL,
    requested_by integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone NOT NULL,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    last_activity_at timestamp without time zone,
    assigned_to integer DEFAULT 0 NOT NULL,
    creator_name character varying(100) NOT NULL,
    creator_email character varying(255) NOT NULL,
    labels text,
    task_id integer NOT NULL,
    closed_at timestamp without time zone NOT NULL,
    merged_with_ticket_id integer NOT NULL,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    cc_contacts_and_emails text,
    client_last_activity_at timestamp without time zone
);


ALTER TABLE public.ncs_tickets OWNER TO postgres;

--
-- Name: ncs_tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_tickets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_tickets_id_seq OWNER TO postgres;

--
-- Name: ncs_tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_tickets_id_seq OWNED BY public.ncs_tickets.id;


--
-- Name: ncs_to_do; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_to_do (
    id integer NOT NULL,
    created_by integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    title text NOT NULL,
    description text,
    labels text,
    status character varying(255) DEFAULT 'to_do'::character varying NOT NULL,
    start_date date,
    deleted smallint DEFAULT '0'::smallint NOT NULL,
    files text NOT NULL,
    sort double precision DEFAULT '1'::double precision NOT NULL
);


ALTER TABLE public.ncs_to_do OWNER TO postgres;

--
-- Name: ncs_to_do_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_to_do_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_to_do_id_seq OWNER TO postgres;

--
-- Name: ncs_to_do_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_to_do_id_seq OWNED BY public.ncs_to_do.id;


--
-- Name: ncs_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_users (
    id integer NOT NULL,
    first_name character varying(50) NOT NULL,
    last_name character varying(50) NOT NULL,
    user_type character varying(255) DEFAULT 'client'::character varying NOT NULL,
    is_admin smallint DEFAULT '0'::smallint NOT NULL,
    role_id integer DEFAULT 0 NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) DEFAULT NULL::character varying,
    image text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    message_checked_at timestamp without time zone,
    client_id integer DEFAULT 0 NOT NULL,
    notification_checked_at timestamp without time zone,
    is_primary_contact smallint DEFAULT '0'::smallint NOT NULL,
    job_title character varying(100) DEFAULT 'Untitled'::character varying NOT NULL,
    disable_login smallint DEFAULT '0'::smallint NOT NULL,
    note text,
    address text,
    alternative_address text,
    phone character varying(20) DEFAULT NULL::character varying,
    alternative_phone character varying(20) DEFAULT NULL::character varying,
    dob date,
    ssn character varying(20) DEFAULT NULL::character varying,
    gender character varying(255) DEFAULT NULL::character varying,
    sticky_note text,
    skype text,
    language character varying(50) NOT NULL,
    enable_web_notification smallint DEFAULT '1'::smallint NOT NULL,
    enable_email_notification smallint DEFAULT '1'::smallint NOT NULL,
    created_at timestamp without time zone,
    last_online timestamp without time zone,
    requested_account_removal smallint DEFAULT '0'::smallint NOT NULL,
    client_permissions text,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_users OWNER TO postgres;

--
-- Name: ncs_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_users_id_seq OWNER TO postgres;

--
-- Name: ncs_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_users_id_seq OWNED BY public.ncs_users.id;


--
-- Name: ncs_verification; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ncs_verification (
    id integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    type character varying(255) NOT NULL,
    code character varying(10) NOT NULL,
    params text NOT NULL,
    deleted integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.ncs_verification OWNER TO postgres;

--
-- Name: ncs_verification_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ncs_verification_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_verification_id_seq OWNER TO postgres;

--
-- Name: ncs_verification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ncs_verification_id_seq OWNED BY public.ncs_verification.id;


--
-- Name: ncs_visitor_appointments; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_visitor_appointments (
    id integer NOT NULL,
    appointment_type character varying(20) DEFAULT 'external'::character varying NOT NULL,
    created_by integer NOT NULL,
    first_name character varying(100),
    last_name character varying(100),
    other_name character varying(100),
    id_type character varying(50),
    id_number character varying(100),
    phone character varying(50),
    email character varying(100),
    organization character varying(150),
    to_user_id integer NOT NULL,
    reason text NOT NULL,
    appointment_date date NOT NULL,
    appointment_time character varying(20) NOT NULL,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    status_by integer,
    status_reason text,
    rescheduled_date date,
    rescheduled_time character varying(20),
    files text,
    deleted integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_visitor_appointments OWNER TO rise_user;

--
-- Name: ncs_visitor_appointments_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_visitor_appointments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_visitor_appointments_id_seq OWNER TO rise_user;

--
-- Name: ncs_visitor_appointments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_visitor_appointments_id_seq OWNED BY public.ncs_visitor_appointments.id;


--
-- Name: ncs_visitor_logbook; Type: TABLE; Schema: public; Owner: rise_user
--

CREATE TABLE public.ncs_visitor_logbook (
    id integer NOT NULL,
    nationality character varying(20) DEFAULT 'ugandan'::character varying NOT NULL,
    first_name character varying(100) NOT NULL,
    last_name character varying(100) NOT NULL,
    other_name character varying(100),
    id_type character varying(50) DEFAULT 'nin'::character varying NOT NULL,
    id_number character varying(100) NOT NULL,
    phone character varying(50),
    email character varying(100),
    organization character varying(150),
    to_user_id integer NOT NULL,
    reason text NOT NULL,
    gate_name character varying(100) DEFAULT 'Main Gate - Lugogo'::character varying NOT NULL,
    time_in timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    time_out timestamp without time zone,
    status character varying(20) DEFAULT 'checked_in'::character varying NOT NULL,
    recorded_by integer NOT NULL,
    checked_out_by integer,
    files text,
    deleted integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.ncs_visitor_logbook OWNER TO rise_user;

--
-- Name: ncs_visitor_logbook_id_seq; Type: SEQUENCE; Schema: public; Owner: rise_user
--

CREATE SEQUENCE public.ncs_visitor_logbook_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ncs_visitor_logbook_id_seq OWNER TO rise_user;

--
-- Name: ncs_visitor_logbook_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rise_user
--

ALTER SEQUENCE public.ncs_visitor_logbook_id_seq OWNED BY public.ncs_visitor_logbook.id;


--
-- Name: ncs_accounting_grants id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_grants ALTER COLUMN id SET DEFAULT nextval('public.ncs_accounting_grants_id_seq'::regclass);


--
-- Name: ncs_accounting_ledgers id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_ledgers ALTER COLUMN id SET DEFAULT nextval('public.ncs_accounting_ledgers_id_seq'::regclass);


--
-- Name: ncs_accounting_reconciliations id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_reconciliations ALTER COLUMN id SET DEFAULT nextval('public.ncs_accounting_reconciliations_id_seq'::regclass);


--
-- Name: ncs_accounting_vote_clearance id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_vote_clearance ALTER COLUMN id SET DEFAULT nextval('public.ncs_accounting_vote_clearance_id_seq'::regclass);


--
-- Name: ncs_activity_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_activity_logs ALTER COLUMN id SET DEFAULT nextval('public.ncs_activity_logs_id_seq'::regclass);


--
-- Name: ncs_admin_appraisals id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_appraisals ALTER COLUMN id SET DEFAULT nextval('public.ncs_admin_appraisals_id_seq'::regclass);


--
-- Name: ncs_admin_approvals id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_approvals ALTER COLUMN id SET DEFAULT nextval('public.ncs_admin_approvals_id_seq'::regclass);


--
-- Name: ncs_admin_board_packages id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_board_packages ALTER COLUMN id SET DEFAULT nextval('public.ncs_admin_board_packages_id_seq'::regclass);


--
-- Name: ncs_admin_federations id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_federations ALTER COLUMN id SET DEFAULT nextval('public.ncs_admin_federations_id_seq'::regclass);


--
-- Name: ncs_announcements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_announcements ALTER COLUMN id SET DEFAULT nextval('public.ncs_announcements_id_seq'::regclass);


--
-- Name: ncs_article_helpful_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_article_helpful_status ALTER COLUMN id SET DEFAULT nextval('public.ncs_article_helpful_status_id_seq'::regclass);


--
-- Name: ncs_asset_transaction_logs id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_asset_transaction_logs ALTER COLUMN id SET DEFAULT nextval('public.ncs_asset_transaction_logs_id_seq'::regclass);


--
-- Name: ncs_attendance id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_attendance ALTER COLUMN id SET DEFAULT nextval('public.ncs_attendance_id_seq'::regclass);


--
-- Name: ncs_audit_discrepancies id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_discrepancies ALTER COLUMN id SET DEFAULT nextval('public.ncs_audit_discrepancies_id_seq'::regclass);


--
-- Name: ncs_audit_reports id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_reports ALTER COLUMN id SET DEFAULT nextval('public.ncs_audit_reports_id_seq'::regclass);


--
-- Name: ncs_automation_settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_automation_settings ALTER COLUMN id SET DEFAULT nextval('public.ncs_automation_settings_id_seq'::regclass);


--
-- Name: ncs_checklist_groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_groups ALTER COLUMN id SET DEFAULT nextval('public.ncs_checklist_groups_id_seq'::regclass);


--
-- Name: ncs_checklist_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_checklist_items_id_seq'::regclass);


--
-- Name: ncs_checklist_template id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_template ALTER COLUMN id SET DEFAULT nextval('public.ncs_checklist_template_id_seq'::regclass);


--
-- Name: ncs_client_groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_client_groups ALTER COLUMN id SET DEFAULT nextval('public.ncs_client_groups_id_seq'::regclass);


--
-- Name: ncs_client_wallet id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_client_wallet ALTER COLUMN id SET DEFAULT nextval('public.ncs_client_wallet_id_seq'::regclass);


--
-- Name: ncs_clients id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_clients ALTER COLUMN id SET DEFAULT nextval('public.ncs_clients_id_seq'::regclass);


--
-- Name: ncs_company id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_company ALTER COLUMN id SET DEFAULT nextval('public.ncs_company_id_seq'::regclass);


--
-- Name: ncs_contract_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contract_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_contract_items_id_seq'::regclass);


--
-- Name: ncs_contract_templates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contract_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_contract_templates_id_seq'::regclass);


--
-- Name: ncs_contracts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contracts ALTER COLUMN id SET DEFAULT nextval('public.ncs_contracts_id_seq'::regclass);


--
-- Name: ncs_custom_field_values id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_field_values ALTER COLUMN id SET DEFAULT nextval('public.ncs_custom_field_values_id_seq'::regclass);


--
-- Name: ncs_custom_fields id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_fields ALTER COLUMN id SET DEFAULT nextval('public.ncs_custom_fields_id_seq'::regclass);


--
-- Name: ncs_custom_widgets id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_widgets ALTER COLUMN id SET DEFAULT nextval('public.ncs_custom_widgets_id_seq'::regclass);


--
-- Name: ncs_dashboards id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_dashboards ALTER COLUMN id SET DEFAULT nextval('public.ncs_dashboards_id_seq'::regclass);


--
-- Name: ncs_departments id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_departments ALTER COLUMN id SET DEFAULT nextval('public.ncs_departments_id_seq'::regclass);


--
-- Name: ncs_e_invoice_templates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_e_invoice_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_e_invoice_templates_id_seq'::regclass);


--
-- Name: ncs_email_templates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_email_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_email_templates_id_seq'::regclass);


--
-- Name: ncs_engineering_assets id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_assets ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_assets_id_seq'::regclass);


--
-- Name: ncs_engineering_capex id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_capex ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_capex_id_seq'::regclass);


--
-- Name: ncs_engineering_civil_assets id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_civil_assets ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_civil_assets_id_seq'::regclass);


--
-- Name: ncs_engineering_electrical_assets id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_electrical_assets ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_electrical_assets_id_seq'::regclass);


--
-- Name: ncs_engineering_inspections id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_inspections ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_inspections_id_seq'::regclass);


--
-- Name: ncs_engineering_technicians id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_technicians ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_technicians_id_seq'::regclass);


--
-- Name: ncs_engineering_work_orders id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_work_orders ALTER COLUMN id SET DEFAULT nextval('public.ncs_engineering_work_orders_id_seq'::regclass);


--
-- Name: ncs_estimate_comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_comments ALTER COLUMN id SET DEFAULT nextval('public.ncs_estimate_comments_id_seq'::regclass);


--
-- Name: ncs_estimate_forms id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_forms ALTER COLUMN id SET DEFAULT nextval('public.ncs_estimate_forms_id_seq'::regclass);


--
-- Name: ncs_estimate_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_estimate_items_id_seq'::regclass);


--
-- Name: ncs_estimate_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_requests ALTER COLUMN id SET DEFAULT nextval('public.ncs_estimate_requests_id_seq'::regclass);


--
-- Name: ncs_estimates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimates ALTER COLUMN id SET DEFAULT nextval('public.ncs_estimates_id_seq'::regclass);


--
-- Name: ncs_event_tracker id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_event_tracker ALTER COLUMN id SET DEFAULT nextval('public.ncs_event_tracker_id_seq'::regclass);


--
-- Name: ncs_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_events ALTER COLUMN id SET DEFAULT nextval('public.ncs_events_id_seq'::regclass);


--
-- Name: ncs_expense_categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_expense_categories ALTER COLUMN id SET DEFAULT nextval('public.ncs_expense_categories_id_seq'::regclass);


--
-- Name: ncs_expenses id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_expenses ALTER COLUMN id SET DEFAULT nextval('public.ncs_expenses_id_seq'::regclass);


--
-- Name: ncs_facilities id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facilities ALTER COLUMN id SET DEFAULT nextval('public.ncs_facilities_id_seq'::regclass);


--
-- Name: ncs_facility_bookings id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facility_bookings ALTER COLUMN id SET DEFAULT nextval('public.ncs_facility_bookings_id_seq'::regclass);


--
-- Name: ncs_facility_inspections id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facility_inspections ALTER COLUMN id SET DEFAULT nextval('public.ncs_facility_inspections_id_seq'::regclass);


--
-- Name: ncs_file_category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_file_category ALTER COLUMN id SET DEFAULT nextval('public.ncs_file_category_id_seq'::regclass);


--
-- Name: ncs_fixed_assets id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fixed_assets ALTER COLUMN id SET DEFAULT nextval('public.ncs_fixed_assets_id_seq'::regclass);


--
-- Name: ncs_fleet_routes id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_routes ALTER COLUMN id SET DEFAULT nextval('public.ncs_fleet_routes_id_seq'::regclass);


--
-- Name: ncs_fleet_service_logs id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_service_logs ALTER COLUMN id SET DEFAULT nextval('public.ncs_fleet_service_logs_id_seq'::regclass);


--
-- Name: ncs_fleet_vehicles id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_vehicles ALTER COLUMN id SET DEFAULT nextval('public.ncs_fleet_vehicles_id_seq'::regclass);


--
-- Name: ncs_folders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_folders ALTER COLUMN id SET DEFAULT nextval('public.ncs_folders_id_seq'::regclass);


--
-- Name: ncs_general_files id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_general_files ALTER COLUMN id SET DEFAULT nextval('public.ncs_general_files_id_seq'::regclass);


--
-- Name: ncs_help_articles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_help_articles ALTER COLUMN id SET DEFAULT nextval('public.ncs_help_articles_id_seq'::regclass);


--
-- Name: ncs_help_categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_help_categories ALTER COLUMN id SET DEFAULT nextval('public.ncs_help_categories_id_seq'::regclass);


--
-- Name: ncs_hostel_occupancies id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hostel_occupancies ALTER COLUMN id SET DEFAULT nextval('public.ncs_hostel_occupancies_id_seq'::regclass);


--
-- Name: ncs_hr_appraisal_items id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_appraisal_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_appraisal_items_id_seq'::regclass);


--
-- Name: ncs_hr_appraisals id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_appraisals ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_appraisals_id_seq'::regclass);


--
-- Name: ncs_hr_memo_recipients id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_memo_recipients ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_memo_recipients_id_seq'::regclass);


--
-- Name: ncs_hr_memos id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_memos ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_memos_id_seq'::regclass);


--
-- Name: ncs_hr_payroll id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_payroll ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_payroll_id_seq'::regclass);


--
-- Name: ncs_hr_profiles id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_profiles ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_profiles_id_seq'::regclass);


--
-- Name: ncs_hr_report_submissions id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_submissions ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_report_submissions_id_seq'::regclass);


--
-- Name: ncs_hr_report_template_fields id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_template_fields ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_report_template_fields_id_seq'::regclass);


--
-- Name: ncs_hr_report_templates id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_hr_report_templates_id_seq'::regclass);


--
-- Name: ncs_ict_equipment id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_equipment ALTER COLUMN id SET DEFAULT nextval('public.ncs_ict_equipment_id_seq'::regclass);


--
-- Name: ncs_ict_expenses id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_expenses ALTER COLUMN id SET DEFAULT nextval('public.ncs_ict_expenses_id_seq'::regclass);


--
-- Name: ncs_ict_helpdesk_tickets id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_helpdesk_tickets ALTER COLUMN id SET DEFAULT nextval('public.ncs_ict_helpdesk_tickets_id_seq'::regclass);


--
-- Name: ncs_ict_issuances id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_issuances ALTER COLUMN id SET DEFAULT nextval('public.ncs_ict_issuances_id_seq'::regclass);


--
-- Name: ncs_ict_maintenance_requisitions id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_maintenance_requisitions ALTER COLUMN id SET DEFAULT nextval('public.ncs_ict_maintenance_requisitions_id_seq'::regclass);


--
-- Name: ncs_invoice_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoice_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_invoice_items_id_seq'::regclass);


--
-- Name: ncs_invoice_payments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoice_payments ALTER COLUMN id SET DEFAULT nextval('public.ncs_invoice_payments_id_seq'::regclass);


--
-- Name: ncs_invoices id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoices ALTER COLUMN id SET DEFAULT nextval('public.ncs_invoices_id_seq'::regclass);


--
-- Name: ncs_item_categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_item_categories ALTER COLUMN id SET DEFAULT nextval('public.ncs_item_categories_id_seq'::regclass);


--
-- Name: ncs_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_items_id_seq'::regclass);


--
-- Name: ncs_labels id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_labels ALTER COLUMN id SET DEFAULT nextval('public.ncs_labels_id_seq'::regclass);


--
-- Name: ncs_lead_source id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_lead_source ALTER COLUMN id SET DEFAULT nextval('public.ncs_lead_source_id_seq'::regclass);


--
-- Name: ncs_lead_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_lead_status ALTER COLUMN id SET DEFAULT nextval('public.ncs_lead_status_id_seq'::regclass);


--
-- Name: ncs_leave_applications id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_leave_applications ALTER COLUMN id SET DEFAULT nextval('public.ncs_leave_applications_id_seq'::regclass);


--
-- Name: ncs_leave_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_leave_types ALTER COLUMN id SET DEFAULT nextval('public.ncs_leave_types_id_seq'::regclass);


--
-- Name: ncs_legal_contracts id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_contracts ALTER COLUMN id SET DEFAULT nextval('public.ncs_legal_contracts_id_seq'::regclass);


--
-- Name: ncs_legal_disputes id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_disputes ALTER COLUMN id SET DEFAULT nextval('public.ncs_legal_disputes_id_seq'::regclass);


--
-- Name: ncs_legal_litigation id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_litigation ALTER COLUMN id SET DEFAULT nextval('public.ncs_legal_litigation_id_seq'::regclass);


--
-- Name: ncs_legal_trademarks id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_trademarks ALTER COLUMN id SET DEFAULT nextval('public.ncs_legal_trademarks_id_seq'::regclass);


--
-- Name: ncs_likes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_likes ALTER COLUMN id SET DEFAULT nextval('public.ncs_likes_id_seq'::regclass);


--
-- Name: ncs_messages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_messages ALTER COLUMN id SET DEFAULT nextval('public.ncs_messages_id_seq'::regclass);


--
-- Name: ncs_milestones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_milestones ALTER COLUMN id SET DEFAULT nextval('public.ncs_milestones_id_seq'::regclass);


--
-- Name: ncs_note_category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_note_category ALTER COLUMN id SET DEFAULT nextval('public.ncs_note_category_id_seq'::regclass);


--
-- Name: ncs_notes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notes ALTER COLUMN id SET DEFAULT nextval('public.ncs_notes_id_seq'::regclass);


--
-- Name: ncs_notification_settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notification_settings ALTER COLUMN id SET DEFAULT nextval('public.ncs_notification_settings_id_seq'::regclass);


--
-- Name: ncs_notifications id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notifications ALTER COLUMN id SET DEFAULT nextval('public.ncs_notifications_id_seq'::regclass);


--
-- Name: ncs_order_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_order_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_order_items_id_seq'::regclass);


--
-- Name: ncs_order_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_order_status ALTER COLUMN id SET DEFAULT nextval('public.ncs_order_status_id_seq'::regclass);


--
-- Name: ncs_orders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_orders ALTER COLUMN id SET DEFAULT nextval('public.ncs_orders_id_seq'::regclass);


--
-- Name: ncs_pages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_pages ALTER COLUMN id SET DEFAULT nextval('public.ncs_pages_id_seq'::regclass);


--
-- Name: ncs_payment_methods id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_payment_methods ALTER COLUMN id SET DEFAULT nextval('public.ncs_payment_methods_id_seq'::regclass);


--
-- Name: ncs_paypal_ipn id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_paypal_ipn ALTER COLUMN id SET DEFAULT nextval('public.ncs_paypal_ipn_id_seq'::regclass);


--
-- Name: ncs_pin_comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_pin_comments ALTER COLUMN id SET DEFAULT nextval('public.ncs_pin_comments_id_seq'::regclass);


--
-- Name: ncs_posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_posts ALTER COLUMN id SET DEFAULT nextval('public.ncs_posts_id_seq'::regclass);


--
-- Name: ncs_procurement_form5 id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5 ALTER COLUMN id SET DEFAULT nextval('public.ncs_procurement_form5_id_seq'::regclass);


--
-- Name: ncs_procurement_form5_items id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_procurement_form5_items_id_seq'::regclass);


--
-- Name: ncs_procurement_plans id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_plans ALTER COLUMN id SET DEFAULT nextval('public.ncs_procurement_plans_id_seq'::regclass);


--
-- Name: ncs_procurement_suppliers id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_suppliers ALTER COLUMN id SET DEFAULT nextval('public.ncs_procurement_suppliers_id_seq'::regclass);


--
-- Name: ncs_project_comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_comments ALTER COLUMN id SET DEFAULT nextval('public.ncs_project_comments_id_seq'::regclass);


--
-- Name: ncs_project_files id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_files ALTER COLUMN id SET DEFAULT nextval('public.ncs_project_files_id_seq'::regclass);


--
-- Name: ncs_project_members id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_members ALTER COLUMN id SET DEFAULT nextval('public.ncs_project_members_id_seq'::regclass);


--
-- Name: ncs_project_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_status ALTER COLUMN id SET DEFAULT nextval('public.ncs_project_status_id_seq'::regclass);


--
-- Name: ncs_project_time id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_time ALTER COLUMN id SET DEFAULT nextval('public.ncs_project_time_id_seq'::regclass);


--
-- Name: ncs_projects id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_projects ALTER COLUMN id SET DEFAULT nextval('public.ncs_projects_id_seq'::regclass);


--
-- Name: ncs_proposal_comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_comments ALTER COLUMN id SET DEFAULT nextval('public.ncs_proposal_comments_id_seq'::regclass);


--
-- Name: ncs_proposal_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_proposal_items_id_seq'::regclass);


--
-- Name: ncs_proposal_templates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_proposal_templates_id_seq'::regclass);


--
-- Name: ncs_proposals id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposals ALTER COLUMN id SET DEFAULT nextval('public.ncs_proposals_id_seq'::regclass);


--
-- Name: ncs_reminder_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_reminder_logs ALTER COLUMN id SET DEFAULT nextval('public.ncs_reminder_logs_id_seq'::regclass);


--
-- Name: ncs_reminder_settings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_reminder_settings ALTER COLUMN id SET DEFAULT nextval('public.ncs_reminder_settings_id_seq'::regclass);


--
-- Name: ncs_roles id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_roles ALTER COLUMN id SET DEFAULT nextval('public.ncs_roles_id_seq'::regclass);


--
-- Name: ncs_store_audit_trail id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_audit_trail ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_audit_trail_id_seq'::regclass);


--
-- Name: ncs_store_grn id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_grn ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_grn_id_seq'::regclass);


--
-- Name: ncs_store_inventory_items id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_inventory_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_inventory_items_id_seq'::regclass);


--
-- Name: ncs_store_issuances id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_issuances ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_issuances_id_seq'::regclass);


--
-- Name: ncs_store_requisitions id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_requisitions ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_requisitions_id_seq'::regclass);


--
-- Name: ncs_store_stock_takes id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_stock_takes ALTER COLUMN id SET DEFAULT nextval('public.ncs_store_stock_takes_id_seq'::regclass);


--
-- Name: ncs_stripe_ipn id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_stripe_ipn ALTER COLUMN id SET DEFAULT nextval('public.ncs_stripe_ipn_id_seq'::regclass);


--
-- Name: ncs_subscription_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_subscription_items ALTER COLUMN id SET DEFAULT nextval('public.ncs_subscription_items_id_seq'::regclass);


--
-- Name: ncs_subscriptions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_subscriptions ALTER COLUMN id SET DEFAULT nextval('public.ncs_subscriptions_id_seq'::regclass);


--
-- Name: ncs_supplier_contacts id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_supplier_contacts ALTER COLUMN id SET DEFAULT nextval('public.ncs_supplier_contacts_id_seq'::regclass);


--
-- Name: ncs_suppliers id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_suppliers ALTER COLUMN id SET DEFAULT nextval('public.ncs_suppliers_id_seq'::regclass);


--
-- Name: ncs_task_priority id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_task_priority ALTER COLUMN id SET DEFAULT nextval('public.ncs_task_priority_id_seq'::regclass);


--
-- Name: ncs_task_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_task_status ALTER COLUMN id SET DEFAULT nextval('public.ncs_task_status_id_seq'::regclass);


--
-- Name: ncs_tasks id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_tasks ALTER COLUMN id SET DEFAULT nextval('public.ncs_tasks_id_seq'::regclass);


--
-- Name: ncs_taxes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_taxes ALTER COLUMN id SET DEFAULT nextval('public.ncs_taxes_id_seq'::regclass);


--
-- Name: ncs_team id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_team ALTER COLUMN id SET DEFAULT nextval('public.ncs_team_id_seq'::regclass);


--
-- Name: ncs_team_member_job_info id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_team_member_job_info ALTER COLUMN id SET DEFAULT nextval('public.ncs_team_member_job_info_id_seq'::regclass);


--
-- Name: ncs_ticket_comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_comments ALTER COLUMN id SET DEFAULT nextval('public.ncs_ticket_comments_id_seq'::regclass);


--
-- Name: ncs_ticket_templates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_templates ALTER COLUMN id SET DEFAULT nextval('public.ncs_ticket_templates_id_seq'::regclass);


--
-- Name: ncs_ticket_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_types ALTER COLUMN id SET DEFAULT nextval('public.ncs_ticket_types_id_seq'::regclass);


--
-- Name: ncs_tickets id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_tickets ALTER COLUMN id SET DEFAULT nextval('public.ncs_tickets_id_seq'::regclass);


--
-- Name: ncs_to_do id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_to_do ALTER COLUMN id SET DEFAULT nextval('public.ncs_to_do_id_seq'::regclass);


--
-- Name: ncs_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_users ALTER COLUMN id SET DEFAULT nextval('public.ncs_users_id_seq'::regclass);


--
-- Name: ncs_verification id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_verification ALTER COLUMN id SET DEFAULT nextval('public.ncs_verification_id_seq'::regclass);


--
-- Name: ncs_visitor_appointments id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_visitor_appointments ALTER COLUMN id SET DEFAULT nextval('public.ncs_visitor_appointments_id_seq'::regclass);


--
-- Name: ncs_visitor_logbook id; Type: DEFAULT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_visitor_logbook ALTER COLUMN id SET DEFAULT nextval('public.ncs_visitor_logbook_id_seq'::regclass);


--
-- Name: ncs_accounting_grants ncs_accounting_grants_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_grants
    ADD CONSTRAINT ncs_accounting_grants_pkey PRIMARY KEY (id);


--
-- Name: ncs_accounting_ledgers ncs_accounting_ledgers_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_ledgers
    ADD CONSTRAINT ncs_accounting_ledgers_pkey PRIMARY KEY (id);


--
-- Name: ncs_accounting_reconciliations ncs_accounting_reconciliations_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_reconciliations
    ADD CONSTRAINT ncs_accounting_reconciliations_pkey PRIMARY KEY (id);


--
-- Name: ncs_accounting_vote_clearance ncs_accounting_vote_clearance_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_accounting_vote_clearance
    ADD CONSTRAINT ncs_accounting_vote_clearance_pkey PRIMARY KEY (id);


--
-- Name: ncs_activity_logs ncs_activity_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_activity_logs
    ADD CONSTRAINT ncs_activity_logs_pkey PRIMARY KEY (id);


--
-- Name: ncs_admin_appraisals ncs_admin_appraisals_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_appraisals
    ADD CONSTRAINT ncs_admin_appraisals_pkey PRIMARY KEY (id);


--
-- Name: ncs_admin_approvals ncs_admin_approvals_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_approvals
    ADD CONSTRAINT ncs_admin_approvals_pkey PRIMARY KEY (id);


--
-- Name: ncs_admin_board_packages ncs_admin_board_packages_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_board_packages
    ADD CONSTRAINT ncs_admin_board_packages_pkey PRIMARY KEY (id);


--
-- Name: ncs_admin_federations ncs_admin_federations_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_admin_federations
    ADD CONSTRAINT ncs_admin_federations_pkey PRIMARY KEY (id);


--
-- Name: ncs_announcements ncs_announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_announcements
    ADD CONSTRAINT ncs_announcements_pkey PRIMARY KEY (id);


--
-- Name: ncs_article_helpful_status ncs_article_helpful_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_article_helpful_status
    ADD CONSTRAINT ncs_article_helpful_status_pkey PRIMARY KEY (id);


--
-- Name: ncs_asset_transaction_logs ncs_asset_transaction_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_asset_transaction_logs
    ADD CONSTRAINT ncs_asset_transaction_logs_pkey PRIMARY KEY (id);


--
-- Name: ncs_attendance ncs_attendance_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_attendance
    ADD CONSTRAINT ncs_attendance_pkey PRIMARY KEY (id);


--
-- Name: ncs_audit_discrepancies ncs_audit_discrepancies_discrepancy_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_discrepancies
    ADD CONSTRAINT ncs_audit_discrepancies_discrepancy_code_key UNIQUE (discrepancy_code);


--
-- Name: ncs_audit_discrepancies ncs_audit_discrepancies_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_discrepancies
    ADD CONSTRAINT ncs_audit_discrepancies_pkey PRIMARY KEY (id);


--
-- Name: ncs_audit_reports ncs_audit_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_reports
    ADD CONSTRAINT ncs_audit_reports_pkey PRIMARY KEY (id);


--
-- Name: ncs_audit_reports ncs_audit_reports_report_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_audit_reports
    ADD CONSTRAINT ncs_audit_reports_report_code_key UNIQUE (report_code);


--
-- Name: ncs_automation_settings ncs_automation_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_automation_settings
    ADD CONSTRAINT ncs_automation_settings_pkey PRIMARY KEY (id);


--
-- Name: ncs_checklist_groups ncs_checklist_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_groups
    ADD CONSTRAINT ncs_checklist_groups_pkey PRIMARY KEY (id);


--
-- Name: ncs_checklist_items ncs_checklist_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_items
    ADD CONSTRAINT ncs_checklist_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_checklist_template ncs_checklist_template_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_checklist_template
    ADD CONSTRAINT ncs_checklist_template_pkey PRIMARY KEY (id);


--
-- Name: ncs_client_groups ncs_client_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_client_groups
    ADD CONSTRAINT ncs_client_groups_pkey PRIMARY KEY (id);


--
-- Name: ncs_client_wallet ncs_client_wallet_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_client_wallet
    ADD CONSTRAINT ncs_client_wallet_pkey PRIMARY KEY (id);


--
-- Name: ncs_clients ncs_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_clients
    ADD CONSTRAINT ncs_clients_pkey PRIMARY KEY (id);


--
-- Name: ncs_company ncs_company_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_company
    ADD CONSTRAINT ncs_company_pkey PRIMARY KEY (id);


--
-- Name: ncs_contract_items ncs_contract_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contract_items
    ADD CONSTRAINT ncs_contract_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_contract_templates ncs_contract_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contract_templates
    ADD CONSTRAINT ncs_contract_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_contracts ncs_contracts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_contracts
    ADD CONSTRAINT ncs_contracts_pkey PRIMARY KEY (id);


--
-- Name: ncs_custom_field_values ncs_custom_field_values_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_field_values
    ADD CONSTRAINT ncs_custom_field_values_pkey PRIMARY KEY (id);


--
-- Name: ncs_custom_fields ncs_custom_fields_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_fields
    ADD CONSTRAINT ncs_custom_fields_pkey PRIMARY KEY (id);


--
-- Name: ncs_custom_widgets ncs_custom_widgets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_custom_widgets
    ADD CONSTRAINT ncs_custom_widgets_pkey PRIMARY KEY (id);


--
-- Name: ncs_dashboards ncs_dashboards_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_dashboards
    ADD CONSTRAINT ncs_dashboards_pkey PRIMARY KEY (id);


--
-- Name: ncs_departments ncs_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_departments
    ADD CONSTRAINT ncs_departments_pkey PRIMARY KEY (id);


--
-- Name: ncs_e_invoice_templates ncs_e_invoice_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_e_invoice_templates
    ADD CONSTRAINT ncs_e_invoice_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_email_templates ncs_email_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_email_templates
    ADD CONSTRAINT ncs_email_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_assets ncs_engineering_assets_asset_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_assets
    ADD CONSTRAINT ncs_engineering_assets_asset_code_key UNIQUE (asset_code);


--
-- Name: ncs_engineering_assets ncs_engineering_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_assets
    ADD CONSTRAINT ncs_engineering_assets_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_capex ncs_engineering_capex_capex_ref_no_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_capex
    ADD CONSTRAINT ncs_engineering_capex_capex_ref_no_key UNIQUE (capex_ref_no);


--
-- Name: ncs_engineering_capex ncs_engineering_capex_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_capex
    ADD CONSTRAINT ncs_engineering_capex_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_civil_assets ncs_engineering_civil_assets_asset_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_civil_assets
    ADD CONSTRAINT ncs_engineering_civil_assets_asset_number_key UNIQUE (asset_number);


--
-- Name: ncs_engineering_civil_assets ncs_engineering_civil_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_civil_assets
    ADD CONSTRAINT ncs_engineering_civil_assets_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_electrical_assets ncs_engineering_electrical_assets_asset_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_electrical_assets
    ADD CONSTRAINT ncs_engineering_electrical_assets_asset_number_key UNIQUE (asset_number);


--
-- Name: ncs_engineering_electrical_assets ncs_engineering_electrical_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_electrical_assets
    ADD CONSTRAINT ncs_engineering_electrical_assets_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_inspections ncs_engineering_inspections_inspection_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_inspections
    ADD CONSTRAINT ncs_engineering_inspections_inspection_code_key UNIQUE (inspection_code);


--
-- Name: ncs_engineering_inspections ncs_engineering_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_inspections
    ADD CONSTRAINT ncs_engineering_inspections_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_technicians ncs_engineering_technicians_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_technicians
    ADD CONSTRAINT ncs_engineering_technicians_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_work_orders ncs_engineering_work_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_work_orders
    ADD CONSTRAINT ncs_engineering_work_orders_pkey PRIMARY KEY (id);


--
-- Name: ncs_engineering_work_orders ncs_engineering_work_orders_wo_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_engineering_work_orders
    ADD CONSTRAINT ncs_engineering_work_orders_wo_number_key UNIQUE (wo_number);


--
-- Name: ncs_estimate_comments ncs_estimate_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_comments
    ADD CONSTRAINT ncs_estimate_comments_pkey PRIMARY KEY (id);


--
-- Name: ncs_estimate_forms ncs_estimate_forms_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_forms
    ADD CONSTRAINT ncs_estimate_forms_pkey PRIMARY KEY (id);


--
-- Name: ncs_estimate_items ncs_estimate_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_items
    ADD CONSTRAINT ncs_estimate_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_estimate_requests ncs_estimate_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimate_requests
    ADD CONSTRAINT ncs_estimate_requests_pkey PRIMARY KEY (id);


--
-- Name: ncs_estimates ncs_estimates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_estimates
    ADD CONSTRAINT ncs_estimates_pkey PRIMARY KEY (id);


--
-- Name: ncs_event_tracker ncs_event_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_event_tracker
    ADD CONSTRAINT ncs_event_tracker_pkey PRIMARY KEY (id);


--
-- Name: ncs_events ncs_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_events
    ADD CONSTRAINT ncs_events_pkey PRIMARY KEY (id);


--
-- Name: ncs_expense_categories ncs_expense_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_expense_categories
    ADD CONSTRAINT ncs_expense_categories_pkey PRIMARY KEY (id);


--
-- Name: ncs_expenses ncs_expenses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_expenses
    ADD CONSTRAINT ncs_expenses_pkey PRIMARY KEY (id);


--
-- Name: ncs_facilities ncs_facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facilities
    ADD CONSTRAINT ncs_facilities_pkey PRIMARY KEY (id);


--
-- Name: ncs_facility_bookings ncs_facility_bookings_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facility_bookings
    ADD CONSTRAINT ncs_facility_bookings_pkey PRIMARY KEY (id);


--
-- Name: ncs_facility_inspections ncs_facility_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_facility_inspections
    ADD CONSTRAINT ncs_facility_inspections_pkey PRIMARY KEY (id);


--
-- Name: ncs_file_category ncs_file_category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_file_category
    ADD CONSTRAINT ncs_file_category_pkey PRIMARY KEY (id);


--
-- Name: ncs_fixed_assets ncs_fixed_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fixed_assets
    ADD CONSTRAINT ncs_fixed_assets_pkey PRIMARY KEY (id);


--
-- Name: ncs_fleet_routes ncs_fleet_routes_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_routes
    ADD CONSTRAINT ncs_fleet_routes_pkey PRIMARY KEY (id);


--
-- Name: ncs_fleet_service_logs ncs_fleet_service_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_service_logs
    ADD CONSTRAINT ncs_fleet_service_logs_pkey PRIMARY KEY (id);


--
-- Name: ncs_fleet_vehicles ncs_fleet_vehicles_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_fleet_vehicles
    ADD CONSTRAINT ncs_fleet_vehicles_pkey PRIMARY KEY (id);


--
-- Name: ncs_folders ncs_folders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_folders
    ADD CONSTRAINT ncs_folders_pkey PRIMARY KEY (id);


--
-- Name: ncs_general_files ncs_general_files_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_general_files
    ADD CONSTRAINT ncs_general_files_pkey PRIMARY KEY (id);


--
-- Name: ncs_help_articles ncs_help_articles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_help_articles
    ADD CONSTRAINT ncs_help_articles_pkey PRIMARY KEY (id);


--
-- Name: ncs_help_categories ncs_help_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_help_categories
    ADD CONSTRAINT ncs_help_categories_pkey PRIMARY KEY (id);


--
-- Name: ncs_hostel_occupancies ncs_hostel_occupancies_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hostel_occupancies
    ADD CONSTRAINT ncs_hostel_occupancies_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_appraisal_items ncs_hr_appraisal_items_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_appraisal_items
    ADD CONSTRAINT ncs_hr_appraisal_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_appraisals ncs_hr_appraisals_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_appraisals
    ADD CONSTRAINT ncs_hr_appraisals_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_memo_recipients ncs_hr_memo_recipients_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_memo_recipients
    ADD CONSTRAINT ncs_hr_memo_recipients_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_memos ncs_hr_memos_memo_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_memos
    ADD CONSTRAINT ncs_hr_memos_memo_number_key UNIQUE (memo_number);


--
-- Name: ncs_hr_memos ncs_hr_memos_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_memos
    ADD CONSTRAINT ncs_hr_memos_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_payroll ncs_hr_payroll_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_payroll
    ADD CONSTRAINT ncs_hr_payroll_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_profiles ncs_hr_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_profiles
    ADD CONSTRAINT ncs_hr_profiles_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_profiles ncs_hr_profiles_user_id_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_profiles
    ADD CONSTRAINT ncs_hr_profiles_user_id_key UNIQUE (user_id);


--
-- Name: ncs_hr_report_submissions ncs_hr_report_submissions_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_submissions
    ADD CONSTRAINT ncs_hr_report_submissions_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_report_template_fields ncs_hr_report_template_fields_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_template_fields
    ADD CONSTRAINT ncs_hr_report_template_fields_pkey PRIMARY KEY (id);


--
-- Name: ncs_hr_report_templates ncs_hr_report_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_hr_report_templates
    ADD CONSTRAINT ncs_hr_report_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_equipment ncs_ict_equipment_item_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_equipment
    ADD CONSTRAINT ncs_ict_equipment_item_code_key UNIQUE (item_code);


--
-- Name: ncs_ict_equipment ncs_ict_equipment_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_equipment
    ADD CONSTRAINT ncs_ict_equipment_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_expenses ncs_ict_expenses_expense_ref_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_expenses
    ADD CONSTRAINT ncs_ict_expenses_expense_ref_key UNIQUE (expense_ref);


--
-- Name: ncs_ict_expenses ncs_ict_expenses_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_expenses
    ADD CONSTRAINT ncs_ict_expenses_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_helpdesk_tickets ncs_ict_helpdesk_tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_helpdesk_tickets
    ADD CONSTRAINT ncs_ict_helpdesk_tickets_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_helpdesk_tickets ncs_ict_helpdesk_tickets_ticket_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_helpdesk_tickets
    ADD CONSTRAINT ncs_ict_helpdesk_tickets_ticket_number_key UNIQUE (ticket_number);


--
-- Name: ncs_ict_issuances ncs_ict_issuances_dispatch_ref_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_issuances
    ADD CONSTRAINT ncs_ict_issuances_dispatch_ref_key UNIQUE (dispatch_ref);


--
-- Name: ncs_ict_issuances ncs_ict_issuances_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_issuances
    ADD CONSTRAINT ncs_ict_issuances_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_maintenance_requisitions ncs_ict_maintenance_requisitions_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_maintenance_requisitions
    ADD CONSTRAINT ncs_ict_maintenance_requisitions_pkey PRIMARY KEY (id);


--
-- Name: ncs_ict_maintenance_requisitions ncs_ict_maintenance_requisitions_req_no_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_ict_maintenance_requisitions
    ADD CONSTRAINT ncs_ict_maintenance_requisitions_req_no_key UNIQUE (req_no);


--
-- Name: ncs_invoice_items ncs_invoice_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoice_items
    ADD CONSTRAINT ncs_invoice_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_invoice_payments ncs_invoice_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoice_payments
    ADD CONSTRAINT ncs_invoice_payments_pkey PRIMARY KEY (id);


--
-- Name: ncs_invoices ncs_invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_invoices
    ADD CONSTRAINT ncs_invoices_pkey PRIMARY KEY (id);


--
-- Name: ncs_item_categories ncs_item_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_item_categories
    ADD CONSTRAINT ncs_item_categories_pkey PRIMARY KEY (id);


--
-- Name: ncs_items ncs_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_items
    ADD CONSTRAINT ncs_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_labels ncs_labels_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_labels
    ADD CONSTRAINT ncs_labels_pkey PRIMARY KEY (id);


--
-- Name: ncs_lead_source ncs_lead_source_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_lead_source
    ADD CONSTRAINT ncs_lead_source_pkey PRIMARY KEY (id);


--
-- Name: ncs_lead_status ncs_lead_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_lead_status
    ADD CONSTRAINT ncs_lead_status_pkey PRIMARY KEY (id);


--
-- Name: ncs_leave_applications ncs_leave_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_leave_applications
    ADD CONSTRAINT ncs_leave_applications_pkey PRIMARY KEY (id);


--
-- Name: ncs_leave_types ncs_leave_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_leave_types
    ADD CONSTRAINT ncs_leave_types_pkey PRIMARY KEY (id);


--
-- Name: ncs_legal_contracts ncs_legal_contracts_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_contracts
    ADD CONSTRAINT ncs_legal_contracts_pkey PRIMARY KEY (id);


--
-- Name: ncs_legal_disputes ncs_legal_disputes_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_disputes
    ADD CONSTRAINT ncs_legal_disputes_pkey PRIMARY KEY (id);


--
-- Name: ncs_legal_litigation ncs_legal_litigation_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_litigation
    ADD CONSTRAINT ncs_legal_litigation_pkey PRIMARY KEY (id);


--
-- Name: ncs_legal_trademarks ncs_legal_trademarks_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_legal_trademarks
    ADD CONSTRAINT ncs_legal_trademarks_pkey PRIMARY KEY (id);


--
-- Name: ncs_likes ncs_likes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_likes
    ADD CONSTRAINT ncs_likes_pkey PRIMARY KEY (id);


--
-- Name: ncs_messages ncs_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_messages
    ADD CONSTRAINT ncs_messages_pkey PRIMARY KEY (id);


--
-- Name: ncs_milestones ncs_milestones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_milestones
    ADD CONSTRAINT ncs_milestones_pkey PRIMARY KEY (id);


--
-- Name: ncs_note_category ncs_note_category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_note_category
    ADD CONSTRAINT ncs_note_category_pkey PRIMARY KEY (id);


--
-- Name: ncs_notes ncs_notes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notes
    ADD CONSTRAINT ncs_notes_pkey PRIMARY KEY (id);


--
-- Name: ncs_notification_settings ncs_notification_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notification_settings
    ADD CONSTRAINT ncs_notification_settings_pkey PRIMARY KEY (id);


--
-- Name: ncs_notifications ncs_notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_notifications
    ADD CONSTRAINT ncs_notifications_pkey PRIMARY KEY (id);


--
-- Name: ncs_order_items ncs_order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_order_items
    ADD CONSTRAINT ncs_order_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_order_status ncs_order_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_order_status
    ADD CONSTRAINT ncs_order_status_pkey PRIMARY KEY (id);


--
-- Name: ncs_orders ncs_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_orders
    ADD CONSTRAINT ncs_orders_pkey PRIMARY KEY (id);


--
-- Name: ncs_pages ncs_pages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_pages
    ADD CONSTRAINT ncs_pages_pkey PRIMARY KEY (id);


--
-- Name: ncs_payment_methods ncs_payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_payment_methods
    ADD CONSTRAINT ncs_payment_methods_pkey PRIMARY KEY (id);


--
-- Name: ncs_paypal_ipn ncs_paypal_ipn_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_paypal_ipn
    ADD CONSTRAINT ncs_paypal_ipn_pkey PRIMARY KEY (id);


--
-- Name: ncs_pin_comments ncs_pin_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_pin_comments
    ADD CONSTRAINT ncs_pin_comments_pkey PRIMARY KEY (id);


--
-- Name: ncs_posts ncs_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_posts
    ADD CONSTRAINT ncs_posts_pkey PRIMARY KEY (id);


--
-- Name: ncs_procurement_form5_items ncs_procurement_form5_items_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5_items
    ADD CONSTRAINT ncs_procurement_form5_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_procurement_form5 ncs_procurement_form5_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5
    ADD CONSTRAINT ncs_procurement_form5_pkey PRIMARY KEY (id);


--
-- Name: ncs_procurement_form5 ncs_procurement_form5_procurement_ref_no_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5
    ADD CONSTRAINT ncs_procurement_form5_procurement_ref_no_key UNIQUE (procurement_ref_no);


--
-- Name: ncs_procurement_plans ncs_procurement_plans_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_plans
    ADD CONSTRAINT ncs_procurement_plans_pkey PRIMARY KEY (id);


--
-- Name: ncs_procurement_plans ncs_procurement_plans_procurement_ref_no_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_plans
    ADD CONSTRAINT ncs_procurement_plans_procurement_ref_no_key UNIQUE (procurement_ref_no);


--
-- Name: ncs_procurement_suppliers ncs_procurement_suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_suppliers
    ADD CONSTRAINT ncs_procurement_suppliers_pkey PRIMARY KEY (id);


--
-- Name: ncs_procurement_suppliers ncs_procurement_suppliers_ppda_registration_no_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_suppliers
    ADD CONSTRAINT ncs_procurement_suppliers_ppda_registration_no_key UNIQUE (ppda_registration_no);


--
-- Name: ncs_project_comments ncs_project_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_comments
    ADD CONSTRAINT ncs_project_comments_pkey PRIMARY KEY (id);


--
-- Name: ncs_project_facility_relations ncs_project_facility_relations_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_project_facility_relations
    ADD CONSTRAINT ncs_project_facility_relations_pkey PRIMARY KEY (project_id);


--
-- Name: ncs_project_files ncs_project_files_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_files
    ADD CONSTRAINT ncs_project_files_pkey PRIMARY KEY (id);


--
-- Name: ncs_project_members ncs_project_members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_members
    ADD CONSTRAINT ncs_project_members_pkey PRIMARY KEY (id);


--
-- Name: ncs_project_status ncs_project_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_status
    ADD CONSTRAINT ncs_project_status_pkey PRIMARY KEY (id);


--
-- Name: ncs_project_time ncs_project_time_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_project_time
    ADD CONSTRAINT ncs_project_time_pkey PRIMARY KEY (id);


--
-- Name: ncs_projects ncs_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_projects
    ADD CONSTRAINT ncs_projects_pkey PRIMARY KEY (id);


--
-- Name: ncs_proposal_comments ncs_proposal_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_comments
    ADD CONSTRAINT ncs_proposal_comments_pkey PRIMARY KEY (id);


--
-- Name: ncs_proposal_items ncs_proposal_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_items
    ADD CONSTRAINT ncs_proposal_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_proposal_templates ncs_proposal_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposal_templates
    ADD CONSTRAINT ncs_proposal_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_proposals ncs_proposals_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_proposals
    ADD CONSTRAINT ncs_proposals_pkey PRIMARY KEY (id);


--
-- Name: ncs_reminder_logs ncs_reminder_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_reminder_logs
    ADD CONSTRAINT ncs_reminder_logs_pkey PRIMARY KEY (id);


--
-- Name: ncs_reminder_settings ncs_reminder_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_reminder_settings
    ADD CONSTRAINT ncs_reminder_settings_pkey PRIMARY KEY (id);


--
-- Name: ncs_roles ncs_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_roles
    ADD CONSTRAINT ncs_roles_pkey PRIMARY KEY (id);


--
-- Name: ncs_social_links ncs_social_links_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_social_links
    ADD CONSTRAINT ncs_social_links_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_audit_trail ncs_store_audit_trail_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_audit_trail
    ADD CONSTRAINT ncs_store_audit_trail_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_grn ncs_store_grn_grn_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_grn
    ADD CONSTRAINT ncs_store_grn_grn_number_key UNIQUE (grn_number);


--
-- Name: ncs_store_grn ncs_store_grn_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_grn
    ADD CONSTRAINT ncs_store_grn_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_inventory_items ncs_store_inventory_items_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_inventory_items
    ADD CONSTRAINT ncs_store_inventory_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_inventory_items ncs_store_inventory_items_sku_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_inventory_items
    ADD CONSTRAINT ncs_store_inventory_items_sku_code_key UNIQUE (sku_code);


--
-- Name: ncs_store_issuances ncs_store_issuances_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_issuances
    ADD CONSTRAINT ncs_store_issuances_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_issuances ncs_store_issuances_siv_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_issuances
    ADD CONSTRAINT ncs_store_issuances_siv_number_key UNIQUE (siv_number);


--
-- Name: ncs_store_requisitions ncs_store_requisitions_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_requisitions
    ADD CONSTRAINT ncs_store_requisitions_pkey PRIMARY KEY (id);


--
-- Name: ncs_store_requisitions ncs_store_requisitions_req_number_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_requisitions
    ADD CONSTRAINT ncs_store_requisitions_req_number_key UNIQUE (req_number);


--
-- Name: ncs_store_stock_takes ncs_store_stock_takes_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_store_stock_takes
    ADD CONSTRAINT ncs_store_stock_takes_pkey PRIMARY KEY (id);


--
-- Name: ncs_stripe_ipn ncs_stripe_ipn_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_stripe_ipn
    ADD CONSTRAINT ncs_stripe_ipn_pkey PRIMARY KEY (id);


--
-- Name: ncs_subscription_items ncs_subscription_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_subscription_items
    ADD CONSTRAINT ncs_subscription_items_pkey PRIMARY KEY (id);


--
-- Name: ncs_subscriptions ncs_subscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_subscriptions
    ADD CONSTRAINT ncs_subscriptions_pkey PRIMARY KEY (id);


--
-- Name: ncs_supplier_contacts ncs_supplier_contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_supplier_contacts
    ADD CONSTRAINT ncs_supplier_contacts_pkey PRIMARY KEY (id);


--
-- Name: ncs_suppliers ncs_suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_suppliers
    ADD CONSTRAINT ncs_suppliers_pkey PRIMARY KEY (id);


--
-- Name: ncs_suppliers ncs_suppliers_supplier_code_key; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_suppliers
    ADD CONSTRAINT ncs_suppliers_supplier_code_key UNIQUE (supplier_code);


--
-- Name: ncs_task_priority ncs_task_priority_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_task_priority
    ADD CONSTRAINT ncs_task_priority_pkey PRIMARY KEY (id);


--
-- Name: ncs_task_status ncs_task_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_task_status
    ADD CONSTRAINT ncs_task_status_pkey PRIMARY KEY (id);


--
-- Name: ncs_tasks ncs_tasks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_tasks
    ADD CONSTRAINT ncs_tasks_pkey PRIMARY KEY (id);


--
-- Name: ncs_taxes ncs_taxes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_taxes
    ADD CONSTRAINT ncs_taxes_pkey PRIMARY KEY (id);


--
-- Name: ncs_team_member_job_info ncs_team_member_job_info_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_team_member_job_info
    ADD CONSTRAINT ncs_team_member_job_info_pkey PRIMARY KEY (id);


--
-- Name: ncs_team ncs_team_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_team
    ADD CONSTRAINT ncs_team_pkey PRIMARY KEY (id);


--
-- Name: ncs_ticket_comments ncs_ticket_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_comments
    ADD CONSTRAINT ncs_ticket_comments_pkey PRIMARY KEY (id);


--
-- Name: ncs_ticket_templates ncs_ticket_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_templates
    ADD CONSTRAINT ncs_ticket_templates_pkey PRIMARY KEY (id);


--
-- Name: ncs_ticket_types ncs_ticket_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_ticket_types
    ADD CONSTRAINT ncs_ticket_types_pkey PRIMARY KEY (id);


--
-- Name: ncs_tickets ncs_tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_tickets
    ADD CONSTRAINT ncs_tickets_pkey PRIMARY KEY (id);


--
-- Name: ncs_to_do ncs_to_do_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_to_do
    ADD CONSTRAINT ncs_to_do_pkey PRIMARY KEY (id);


--
-- Name: ncs_users ncs_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_users
    ADD CONSTRAINT ncs_users_pkey PRIMARY KEY (id);


--
-- Name: ncs_verification ncs_verification_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ncs_verification
    ADD CONSTRAINT ncs_verification_pkey PRIMARY KEY (id);


--
-- Name: ncs_visitor_appointments ncs_visitor_appointments_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_visitor_appointments
    ADD CONSTRAINT ncs_visitor_appointments_pkey PRIMARY KEY (id);


--
-- Name: ncs_visitor_logbook ncs_visitor_logbook_pkey; Type: CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_visitor_logbook
    ADD CONSTRAINT ncs_visitor_logbook_pkey PRIMARY KEY (id);


--
-- Name: assigned_to_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX assigned_to_ncs_tasks_idx ON public.ncs_tasks USING btree (assigned_to);


--
-- Name: assigned_to_ncs_tickets_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX assigned_to_ncs_tickets_idx ON public.ncs_tickets USING btree (assigned_to);


--
-- Name: category_id_ncs_expenses_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX category_id_ncs_expenses_idx ON public.ncs_expenses USING btree (category_id);


--
-- Name: checked_by_ncs_attendance_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX checked_by_ncs_attendance_idx ON public.ncs_attendance USING btree (checked_by);


--
-- Name: checked_by_ncs_leave_applications_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX checked_by_ncs_leave_applications_idx ON public.ncs_leave_applications USING btree (checked_by);


--
-- Name: ci_sessions_timestamp_ncs_ci_sessions_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ci_sessions_timestamp_ncs_ci_sessions_idx ON public.ncs_ci_sessions USING btree ("timestamp");


--
-- Name: client_id_ncs_invoices_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX client_id_ncs_invoices_idx ON public.ncs_invoices USING btree (client_id);


--
-- Name: client_id_ncs_projects_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX client_id_ncs_projects_idx ON public.ncs_projects USING btree (client_id);


--
-- Name: client_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX client_id_ncs_tasks_idx ON public.ncs_tasks USING btree (client_id);


--
-- Name: client_id_ncs_tickets_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX client_id_ncs_tickets_idx ON public.ncs_tickets USING btree (client_id);


--
-- Name: client_id_ncs_users_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX client_id_ncs_users_idx ON public.ncs_users USING btree (client_id);


--
-- Name: context_ncs_labels_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX context_ncs_labels_idx ON public.ncs_labels USING btree (context);


--
-- Name: contract_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX contract_id_ncs_tasks_idx ON public.ncs_tasks USING btree (contract_id);


--
-- Name: created_by_ncs_activity_logs_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX created_by_ncs_activity_logs_idx ON public.ncs_activity_logs USING btree (created_by);


--
-- Name: created_by_ncs_announcements_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX created_by_ncs_announcements_idx ON public.ncs_announcements USING btree (created_by);


--
-- Name: created_by_ncs_clients_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX created_by_ncs_clients_idx ON public.ncs_clients USING btree (created_by);


--
-- Name: created_by_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX created_by_ncs_events_idx ON public.ncs_events USING btree (created_by);


--
-- Name: created_by_ncs_to_do_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX created_by_ncs_to_do_idx ON public.ncs_to_do USING btree (created_by);


--
-- Name: custom_field_id_ncs_custom_field_values_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX custom_field_id_ncs_custom_field_values_idx ON public.ncs_custom_field_values USING btree (custom_field_id);


--
-- Name: deleted_ncs_users_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX deleted_ncs_users_idx ON public.ncs_users USING btree (deleted);


--
-- Name: due_date_ncs_invoices_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX due_date_ncs_invoices_idx ON public.ncs_invoices USING btree (due_date);


--
-- Name: email_ncs_users_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX email_ncs_users_idx ON public.ncs_users USING btree (email);


--
-- Name: end_date_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX end_date_ncs_events_idx ON public.ncs_events USING btree (end_date);


--
-- Name: estimate_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX estimate_id_ncs_tasks_idx ON public.ncs_tasks USING btree (estimate_id);


--
-- Name: event_ncs_notification_settings_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX event_ncs_notification_settings_idx ON public.ncs_notification_settings USING btree (event);


--
-- Name: expense_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX expense_id_ncs_tasks_idx ON public.ncs_tasks USING btree (expense_id);


--
-- Name: field_type_ncs_custom_fields_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX field_type_ncs_custom_fields_idx ON public.ncs_custom_fields USING btree (field_type);


--
-- Name: id_2_ncs_invoice_payments_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX id_2_ncs_invoice_payments_idx ON public.ncs_invoice_payments USING btree (id);


--
-- Name: id_ncs_client_wallet_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX id_ncs_client_wallet_idx ON public.ncs_client_wallet USING btree (id);


--
-- Name: id_ncs_invoice_payments_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX id_ncs_invoice_payments_idx ON public.ncs_invoice_payments USING btree (id);


--
-- Name: invoice_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX invoice_id_ncs_tasks_idx ON public.ncs_tasks USING btree (invoice_id);


--
-- Name: is_lead_ncs_clients_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX is_lead_ncs_clients_idx ON public.ncs_clients USING btree (is_lead);


--
-- Name: lead_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX lead_id_ncs_tasks_idx ON public.ncs_tasks USING btree (lead_id);


--
-- Name: lead_source_id_ncs_clients_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX lead_source_id_ncs_clients_idx ON public.ncs_clients USING btree (lead_source_id);


--
-- Name: leave_type_id_ncs_leave_applications_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX leave_type_id_ncs_leave_applications_idx ON public.ncs_leave_applications USING btree (leave_type_id);


--
-- Name: log_for2_ncs_activity_logs_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX log_for2_ncs_activity_logs_idx ON public.ncs_activity_logs USING btree (log_for2, log_for_id2);


--
-- Name: log_for_ncs_activity_logs_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX log_for_ncs_activity_logs_idx ON public.ncs_activity_logs USING btree (log_for, log_for_id);


--
-- Name: log_type_ncs_activity_logs_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX log_type_ncs_activity_logs_idx ON public.ncs_activity_logs USING btree (log_type, log_type_id);


--
-- Name: message_from_ncs_messages_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX message_from_ncs_messages_idx ON public.ncs_messages USING btree (from_user_id);


--
-- Name: message_to_ncs_messages_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX message_to_ncs_messages_idx ON public.ncs_messages USING btree (to_user_id);


--
-- Name: milestone_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX milestone_id_ncs_tasks_idx ON public.ncs_tasks USING btree (milestone_id);


--
-- Name: order_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX order_id_ncs_tasks_idx ON public.ncs_tasks USING btree (order_id);


--
-- Name: owner_id_ncs_clients_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX owner_id_ncs_clients_idx ON public.ncs_clients USING btree (owner_id);


--
-- Name: priority_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX priority_id_ncs_tasks_idx ON public.ncs_tasks USING btree (priority_id);


--
-- Name: project_id_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX project_id_ncs_events_idx ON public.ncs_events USING btree (project_id);


--
-- Name: project_id_ncs_invoices_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX project_id_ncs_invoices_idx ON public.ncs_invoices USING btree (project_id);


--
-- Name: project_id_ncs_project_members_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX project_id_ncs_project_members_idx ON public.ncs_project_members USING btree (project_id);


--
-- Name: project_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX project_id_ncs_tasks_idx ON public.ncs_tasks USING btree (project_id);


--
-- Name: proposal_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX proposal_id_ncs_tasks_idx ON public.ncs_tasks USING btree (proposal_id);


--
-- Name: recurring_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX recurring_ncs_events_idx ON public.ncs_events USING btree (recurring);


--
-- Name: related_to_id_ncs_custom_field_values_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX related_to_id_ncs_custom_field_values_idx ON public.ncs_custom_field_values USING btree (related_to_id);


--
-- Name: related_to_ncs_custom_fields_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX related_to_ncs_custom_fields_idx ON public.ncs_custom_fields USING btree (related_to);


--
-- Name: related_to_type_ncs_custom_field_values_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX related_to_type_ncs_custom_field_values_idx ON public.ncs_custom_field_values USING btree (related_to_type);


--
-- Name: setting_name; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX setting_name ON public.ncs_settings USING btree (setting_name);


--
-- Name: sort_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sort_ncs_tasks_idx ON public.ncs_tasks USING btree (sort);


--
-- Name: start_date_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX start_date_ncs_events_idx ON public.ncs_events USING btree (start_date);


--
-- Name: status_id_ncs_projects_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX status_id_ncs_projects_idx ON public.ncs_projects USING btree (status_id);


--
-- Name: status_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX status_id_ncs_tasks_idx ON public.ncs_tasks USING btree (status_id);


--
-- Name: status_ncs_invoices_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX status_ncs_invoices_idx ON public.ncs_invoices USING btree (status);


--
-- Name: subscription_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX subscription_id_ncs_tasks_idx ON public.ncs_tasks USING btree (subscription_id);


--
-- Name: task_id_ncs_events_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX task_id_ncs_events_idx ON public.ncs_events USING btree (task_id);


--
-- Name: ticket_id_ncs_tasks_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ticket_id_ncs_tasks_idx ON public.ncs_tasks USING btree (ticket_id);


--
-- Name: ticket_type_id_ncs_tickets_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ticket_type_id_ncs_tickets_idx ON public.ncs_tickets USING btree (ticket_type_id);


--
-- Name: unique_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX unique_index ON public.ncs_project_settings USING btree (project_id, setting_name);


--
-- Name: user_id_ncs_attendance_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_attendance_idx ON public.ncs_attendance USING btree (user_id);


--
-- Name: user_id_ncs_custom_widgets_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_custom_widgets_idx ON public.ncs_custom_widgets USING btree (user_id);


--
-- Name: user_id_ncs_dashboards_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_dashboards_idx ON public.ncs_dashboards USING btree (user_id);


--
-- Name: user_id_ncs_leave_applications_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_leave_applications_idx ON public.ncs_leave_applications USING btree (applicant_id);


--
-- Name: user_id_ncs_notifications_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_notifications_idx ON public.ncs_notifications USING btree (user_id);


--
-- Name: user_id_ncs_project_members_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_project_members_idx ON public.ncs_project_members USING btree (user_id);


--
-- Name: user_id_ncs_team_member_job_info_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_id_ncs_team_member_job_info_idx ON public.ncs_team_member_job_info USING btree (user_id);


--
-- Name: user_type_ncs_users_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_type_ncs_users_idx ON public.ncs_users USING btree (user_type);


--
-- Name: ncs_procurement_form5_items ncs_procurement_form5_items_form5_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_procurement_form5_items
    ADD CONSTRAINT ncs_procurement_form5_items_form5_id_fkey FOREIGN KEY (form5_id) REFERENCES public.ncs_procurement_form5(id) ON DELETE CASCADE;


--
-- Name: ncs_supplier_contacts ncs_supplier_contacts_supplier_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: rise_user
--

ALTER TABLE ONLY public.ncs_supplier_contacts
    ADD CONSTRAINT ncs_supplier_contacts_supplier_id_fkey FOREIGN KEY (supplier_id) REFERENCES public.ncs_suppliers(id) ON DELETE CASCADE;


--
-- Name: FUNCTION date(timestamp without time zone); Type: ACL; Schema: pg_catalog; Owner: postgres
--

GRANT ALL ON FUNCTION pg_catalog.date(timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION addtime(ts timestamp without time zone, t text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.addtime(ts timestamp without time zone, t text) TO rise_user;


--
-- Name: FUNCTION convert_tz(dt timestamp without time zone, from_tz text, to_tz text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.convert_tz(dt timestamp without time zone, from_tz text, to_tz text) TO rise_user;


--
-- Name: FUNCTION date_add(d date, interval_expr text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.date_add(d date, interval_expr text) TO rise_user;


--
-- Name: FUNCTION date_format(d date, fmt text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.date_format(d date, fmt text) TO rise_user;


--
-- Name: FUNCTION date_format(d timestamp without time zone, fmt text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.date_format(d timestamp without time zone, fmt text) TO rise_user;


--
-- Name: FUNCTION datediff(d1 date, d2 date); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.datediff(d1 date, d2 date) TO rise_user;


--
-- Name: FUNCTION datediff(d1 text, d2 text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.datediff(d1 text, d2 text) TO rise_user;


--
-- Name: FUNCTION datediff(d1 timestamp without time zone, d2 timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.datediff(d1 timestamp without time zone, d2 timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION day(d date); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.day(d date) TO rise_user;


--
-- Name: FUNCTION day(d timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.day(d timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION find_in_set(needle integer, haystack text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.find_in_set(needle integer, haystack text) TO rise_user;


--
-- Name: FUNCTION find_in_set(needle text, haystack text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.find_in_set(needle text, haystack text) TO rise_user;


--
-- Name: FUNCTION ifnull(anyelement, anyelement); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.ifnull(anyelement, anyelement) TO rise_user;


--
-- Name: FUNCTION month(d date); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.month(d date) TO rise_user;


--
-- Name: FUNCTION month(d timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.month(d timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION mysql_if(boolean, anyelement, anyelement); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.mysql_if(boolean, anyelement, anyelement) TO rise_user;


--
-- Name: FUNCTION str_to_date(str text, fmt text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.str_to_date(str text, fmt text) TO rise_user;


--
-- Name: FUNCTION timestampdiff(unit text, ts1 timestamp without time zone, ts2 timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.timestampdiff(unit text, ts1 timestamp without time zone, ts2 timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION trunc(val double precision, scale integer); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.trunc(val double precision, scale integer) TO rise_user;


--
-- Name: FUNCTION unix_timestamp(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.unix_timestamp() TO rise_user;


--
-- Name: FUNCTION unix_timestamp(ts timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.unix_timestamp(ts timestamp without time zone) TO rise_user;


--
-- Name: FUNCTION year(d date); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.year(d date) TO rise_user;


--
-- Name: FUNCTION year(d timestamp without time zone); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.year(d timestamp without time zone) TO rise_user;


--
-- Name: TABLE ncs_activity_logs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_activity_logs TO rise_user;


--
-- Name: SEQUENCE ncs_activity_logs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_activity_logs_id_seq TO rise_user;


--
-- Name: TABLE ncs_announcements; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_announcements TO rise_user;


--
-- Name: SEQUENCE ncs_announcements_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_announcements_id_seq TO rise_user;


--
-- Name: TABLE ncs_article_helpful_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_article_helpful_status TO rise_user;


--
-- Name: SEQUENCE ncs_article_helpful_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_article_helpful_status_id_seq TO rise_user;


--
-- Name: TABLE ncs_attendance; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_attendance TO rise_user;


--
-- Name: SEQUENCE ncs_attendance_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_attendance_id_seq TO rise_user;


--
-- Name: TABLE ncs_automation_settings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_automation_settings TO rise_user;


--
-- Name: SEQUENCE ncs_automation_settings_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_automation_settings_id_seq TO rise_user;


--
-- Name: TABLE ncs_checklist_groups; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_checklist_groups TO rise_user;


--
-- Name: SEQUENCE ncs_checklist_groups_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_checklist_groups_id_seq TO rise_user;


--
-- Name: TABLE ncs_checklist_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_checklist_items TO rise_user;


--
-- Name: SEQUENCE ncs_checklist_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_checklist_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_checklist_template; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_checklist_template TO rise_user;


--
-- Name: SEQUENCE ncs_checklist_template_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_checklist_template_id_seq TO rise_user;


--
-- Name: TABLE ncs_ci_sessions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_ci_sessions TO rise_user;


--
-- Name: TABLE ncs_client_groups; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_client_groups TO rise_user;


--
-- Name: SEQUENCE ncs_client_groups_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_client_groups_id_seq TO rise_user;


--
-- Name: TABLE ncs_client_wallet; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_client_wallet TO rise_user;


--
-- Name: SEQUENCE ncs_client_wallet_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_client_wallet_id_seq TO rise_user;


--
-- Name: TABLE ncs_clients; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_clients TO rise_user;


--
-- Name: SEQUENCE ncs_clients_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_clients_id_seq TO rise_user;


--
-- Name: TABLE ncs_company; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_company TO rise_user;


--
-- Name: SEQUENCE ncs_company_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_company_id_seq TO rise_user;


--
-- Name: TABLE ncs_contract_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_contract_items TO rise_user;


--
-- Name: SEQUENCE ncs_contract_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_contract_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_contract_templates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_contract_templates TO rise_user;


--
-- Name: SEQUENCE ncs_contract_templates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_contract_templates_id_seq TO rise_user;


--
-- Name: TABLE ncs_contracts; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_contracts TO rise_user;


--
-- Name: SEQUENCE ncs_contracts_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_contracts_id_seq TO rise_user;


--
-- Name: TABLE ncs_custom_field_values; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_custom_field_values TO rise_user;


--
-- Name: SEQUENCE ncs_custom_field_values_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_custom_field_values_id_seq TO rise_user;


--
-- Name: TABLE ncs_custom_fields; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_custom_fields TO rise_user;


--
-- Name: SEQUENCE ncs_custom_fields_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_custom_fields_id_seq TO rise_user;


--
-- Name: TABLE ncs_custom_widgets; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_custom_widgets TO rise_user;


--
-- Name: SEQUENCE ncs_custom_widgets_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_custom_widgets_id_seq TO rise_user;


--
-- Name: TABLE ncs_dashboards; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_dashboards TO rise_user;


--
-- Name: SEQUENCE ncs_dashboards_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_dashboards_id_seq TO rise_user;


--
-- Name: TABLE ncs_e_invoice_templates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_e_invoice_templates TO rise_user;


--
-- Name: SEQUENCE ncs_e_invoice_templates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_e_invoice_templates_id_seq TO rise_user;


--
-- Name: TABLE ncs_email_templates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_email_templates TO rise_user;


--
-- Name: SEQUENCE ncs_email_templates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_email_templates_id_seq TO rise_user;


--
-- Name: TABLE ncs_estimate_comments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_estimate_comments TO rise_user;


--
-- Name: SEQUENCE ncs_estimate_comments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_estimate_comments_id_seq TO rise_user;


--
-- Name: TABLE ncs_estimate_forms; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_estimate_forms TO rise_user;


--
-- Name: SEQUENCE ncs_estimate_forms_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_estimate_forms_id_seq TO rise_user;


--
-- Name: TABLE ncs_estimate_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_estimate_items TO rise_user;


--
-- Name: SEQUENCE ncs_estimate_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_estimate_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_estimate_requests; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_estimate_requests TO rise_user;


--
-- Name: SEQUENCE ncs_estimate_requests_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_estimate_requests_id_seq TO rise_user;


--
-- Name: TABLE ncs_estimates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_estimates TO rise_user;


--
-- Name: SEQUENCE ncs_estimates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_estimates_id_seq TO rise_user;


--
-- Name: TABLE ncs_event_tracker; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_event_tracker TO rise_user;


--
-- Name: SEQUENCE ncs_event_tracker_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_event_tracker_id_seq TO rise_user;


--
-- Name: TABLE ncs_events; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_events TO rise_user;


--
-- Name: SEQUENCE ncs_events_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_events_id_seq TO rise_user;


--
-- Name: TABLE ncs_expense_categories; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_expense_categories TO rise_user;


--
-- Name: SEQUENCE ncs_expense_categories_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_expense_categories_id_seq TO rise_user;


--
-- Name: TABLE ncs_expenses; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_expenses TO rise_user;


--
-- Name: SEQUENCE ncs_expenses_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_expenses_id_seq TO rise_user;


--
-- Name: TABLE ncs_file_category; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_file_category TO rise_user;


--
-- Name: SEQUENCE ncs_file_category_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_file_category_id_seq TO rise_user;


--
-- Name: TABLE ncs_folders; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_folders TO rise_user;


--
-- Name: SEQUENCE ncs_folders_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_folders_id_seq TO rise_user;


--
-- Name: TABLE ncs_general_files; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_general_files TO rise_user;


--
-- Name: SEQUENCE ncs_general_files_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_general_files_id_seq TO rise_user;


--
-- Name: TABLE ncs_help_articles; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_help_articles TO rise_user;


--
-- Name: SEQUENCE ncs_help_articles_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_help_articles_id_seq TO rise_user;


--
-- Name: TABLE ncs_help_categories; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_help_categories TO rise_user;


--
-- Name: SEQUENCE ncs_help_categories_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_help_categories_id_seq TO rise_user;


--
-- Name: TABLE ncs_invoice_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_invoice_items TO rise_user;


--
-- Name: SEQUENCE ncs_invoice_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_invoice_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_invoice_payments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_invoice_payments TO rise_user;


--
-- Name: SEQUENCE ncs_invoice_payments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_invoice_payments_id_seq TO rise_user;


--
-- Name: TABLE ncs_invoices; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_invoices TO rise_user;


--
-- Name: SEQUENCE ncs_invoices_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_invoices_id_seq TO rise_user;


--
-- Name: TABLE ncs_item_categories; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_item_categories TO rise_user;


--
-- Name: SEQUENCE ncs_item_categories_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_item_categories_id_seq TO rise_user;


--
-- Name: TABLE ncs_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_items TO rise_user;


--
-- Name: SEQUENCE ncs_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_labels; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_labels TO rise_user;


--
-- Name: SEQUENCE ncs_labels_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_labels_id_seq TO rise_user;


--
-- Name: TABLE ncs_lead_source; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_lead_source TO rise_user;


--
-- Name: SEQUENCE ncs_lead_source_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_lead_source_id_seq TO rise_user;


--
-- Name: TABLE ncs_lead_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_lead_status TO rise_user;


--
-- Name: SEQUENCE ncs_lead_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_lead_status_id_seq TO rise_user;


--
-- Name: TABLE ncs_leave_applications; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_leave_applications TO rise_user;


--
-- Name: SEQUENCE ncs_leave_applications_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_leave_applications_id_seq TO rise_user;


--
-- Name: TABLE ncs_leave_types; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_leave_types TO rise_user;


--
-- Name: SEQUENCE ncs_leave_types_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_leave_types_id_seq TO rise_user;


--
-- Name: TABLE ncs_likes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_likes TO rise_user;


--
-- Name: SEQUENCE ncs_likes_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_likes_id_seq TO rise_user;


--
-- Name: TABLE ncs_messages; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_messages TO rise_user;


--
-- Name: SEQUENCE ncs_messages_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_messages_id_seq TO rise_user;


--
-- Name: TABLE ncs_milestones; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_milestones TO rise_user;


--
-- Name: SEQUENCE ncs_milestones_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_milestones_id_seq TO rise_user;


--
-- Name: TABLE ncs_note_category; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_note_category TO rise_user;


--
-- Name: SEQUENCE ncs_note_category_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_note_category_id_seq TO rise_user;


--
-- Name: TABLE ncs_notes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_notes TO rise_user;


--
-- Name: SEQUENCE ncs_notes_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_notes_id_seq TO rise_user;


--
-- Name: TABLE ncs_notification_settings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_notification_settings TO rise_user;


--
-- Name: SEQUENCE ncs_notification_settings_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_notification_settings_id_seq TO rise_user;


--
-- Name: TABLE ncs_notifications; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_notifications TO rise_user;


--
-- Name: SEQUENCE ncs_notifications_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_notifications_id_seq TO rise_user;


--
-- Name: TABLE ncs_order_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_order_items TO rise_user;


--
-- Name: SEQUENCE ncs_order_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_order_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_order_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_order_status TO rise_user;


--
-- Name: SEQUENCE ncs_order_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_order_status_id_seq TO rise_user;


--
-- Name: TABLE ncs_orders; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_orders TO rise_user;


--
-- Name: SEQUENCE ncs_orders_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_orders_id_seq TO rise_user;


--
-- Name: TABLE ncs_pages; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_pages TO rise_user;


--
-- Name: SEQUENCE ncs_pages_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_pages_id_seq TO rise_user;


--
-- Name: TABLE ncs_payment_methods; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_payment_methods TO rise_user;


--
-- Name: SEQUENCE ncs_payment_methods_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_payment_methods_id_seq TO rise_user;


--
-- Name: TABLE ncs_paypal_ipn; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_paypal_ipn TO rise_user;


--
-- Name: SEQUENCE ncs_paypal_ipn_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_paypal_ipn_id_seq TO rise_user;


--
-- Name: TABLE ncs_pin_comments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_pin_comments TO rise_user;


--
-- Name: SEQUENCE ncs_pin_comments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_pin_comments_id_seq TO rise_user;


--
-- Name: TABLE ncs_posts; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_posts TO rise_user;


--
-- Name: SEQUENCE ncs_posts_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_posts_id_seq TO rise_user;


--
-- Name: TABLE ncs_project_comments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_comments TO rise_user;


--
-- Name: SEQUENCE ncs_project_comments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_project_comments_id_seq TO rise_user;


--
-- Name: TABLE ncs_project_files; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_files TO rise_user;


--
-- Name: SEQUENCE ncs_project_files_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_project_files_id_seq TO rise_user;


--
-- Name: TABLE ncs_project_members; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_members TO rise_user;


--
-- Name: SEQUENCE ncs_project_members_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_project_members_id_seq TO rise_user;


--
-- Name: TABLE ncs_project_settings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_settings TO rise_user;


--
-- Name: TABLE ncs_project_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_status TO rise_user;


--
-- Name: SEQUENCE ncs_project_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_project_status_id_seq TO rise_user;


--
-- Name: TABLE ncs_project_time; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_project_time TO rise_user;


--
-- Name: SEQUENCE ncs_project_time_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_project_time_id_seq TO rise_user;


--
-- Name: TABLE ncs_projects; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_projects TO rise_user;


--
-- Name: SEQUENCE ncs_projects_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_projects_id_seq TO rise_user;


--
-- Name: TABLE ncs_proposal_comments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_proposal_comments TO rise_user;


--
-- Name: SEQUENCE ncs_proposal_comments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_proposal_comments_id_seq TO rise_user;


--
-- Name: TABLE ncs_proposal_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_proposal_items TO rise_user;


--
-- Name: SEQUENCE ncs_proposal_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_proposal_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_proposal_templates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_proposal_templates TO rise_user;


--
-- Name: SEQUENCE ncs_proposal_templates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_proposal_templates_id_seq TO rise_user;


--
-- Name: TABLE ncs_proposals; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_proposals TO rise_user;


--
-- Name: SEQUENCE ncs_proposals_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_proposals_id_seq TO rise_user;


--
-- Name: TABLE ncs_reminder_logs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_reminder_logs TO rise_user;


--
-- Name: SEQUENCE ncs_reminder_logs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_reminder_logs_id_seq TO rise_user;


--
-- Name: TABLE ncs_reminder_settings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_reminder_settings TO rise_user;


--
-- Name: SEQUENCE ncs_reminder_settings_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_reminder_settings_id_seq TO rise_user;


--
-- Name: TABLE ncs_settings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_settings TO rise_user;


--
-- Name: TABLE ncs_social_links; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_social_links TO rise_user;


--
-- Name: TABLE ncs_stripe_ipn; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_stripe_ipn TO rise_user;


--
-- Name: SEQUENCE ncs_stripe_ipn_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_stripe_ipn_id_seq TO rise_user;


--
-- Name: TABLE ncs_subscription_items; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_subscription_items TO rise_user;


--
-- Name: SEQUENCE ncs_subscription_items_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_subscription_items_id_seq TO rise_user;


--
-- Name: TABLE ncs_subscriptions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_subscriptions TO rise_user;


--
-- Name: SEQUENCE ncs_subscriptions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_subscriptions_id_seq TO rise_user;


--
-- Name: TABLE ncs_task_priority; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_task_priority TO rise_user;


--
-- Name: SEQUENCE ncs_task_priority_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_task_priority_id_seq TO rise_user;


--
-- Name: TABLE ncs_task_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_task_status TO rise_user;


--
-- Name: SEQUENCE ncs_task_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_task_status_id_seq TO rise_user;


--
-- Name: TABLE ncs_tasks; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_tasks TO rise_user;


--
-- Name: SEQUENCE ncs_tasks_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_tasks_id_seq TO rise_user;


--
-- Name: TABLE ncs_taxes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_taxes TO rise_user;


--
-- Name: SEQUENCE ncs_taxes_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_taxes_id_seq TO rise_user;


--
-- Name: TABLE ncs_team; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_team TO rise_user;


--
-- Name: SEQUENCE ncs_team_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_team_id_seq TO rise_user;


--
-- Name: TABLE ncs_team_member_job_info; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_team_member_job_info TO rise_user;


--
-- Name: SEQUENCE ncs_team_member_job_info_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_team_member_job_info_id_seq TO rise_user;


--
-- Name: TABLE ncs_ticket_comments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_ticket_comments TO rise_user;


--
-- Name: SEQUENCE ncs_ticket_comments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_ticket_comments_id_seq TO rise_user;


--
-- Name: TABLE ncs_ticket_templates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_ticket_templates TO rise_user;


--
-- Name: SEQUENCE ncs_ticket_templates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_ticket_templates_id_seq TO rise_user;


--
-- Name: TABLE ncs_ticket_types; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_ticket_types TO rise_user;


--
-- Name: SEQUENCE ncs_ticket_types_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_ticket_types_id_seq TO rise_user;


--
-- Name: TABLE ncs_tickets; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_tickets TO rise_user;


--
-- Name: SEQUENCE ncs_tickets_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_tickets_id_seq TO rise_user;


--
-- Name: TABLE ncs_to_do; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_to_do TO rise_user;


--
-- Name: SEQUENCE ncs_to_do_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_to_do_id_seq TO rise_user;


--
-- Name: TABLE ncs_users; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_users TO rise_user;


--
-- Name: SEQUENCE ncs_users_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_users_id_seq TO rise_user;


--
-- Name: TABLE ncs_verification; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ncs_verification TO rise_user;


--
-- Name: SEQUENCE ncs_verification_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ncs_verification_id_seq TO rise_user;


--
-- PostgreSQL database dump complete
--

\unrestrict jH00HW985Tf1zRL9HaWVJMzj5ICSTe0kNQa0W9LLvkshMYgemxFHYygXlBAhXtp

